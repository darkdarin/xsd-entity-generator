<?php

namespace DarkDarin\XsdEntityGenerator;

use DarkDarin\PhpEntityRenderer\Contracts\EntityRendererInterface;
use DarkDarin\PhpEntityRenderer\Contracts\EntityWithDescriptionInterface;
use DarkDarin\PhpEntityRenderer\Contracts\TypeRendererInterface;
use DarkDarin\PhpEntityRenderer\EntityAliases;
use DarkDarin\PhpEntityRenderer\Enums\EnumTypeEnum;
use DarkDarin\PhpEntityRenderer\Enums\VisibilityModifierEnum;
use DarkDarin\PhpEntityRenderer\Renderers\AttributeRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\ClassRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\EnumCaseRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\EnumRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\MethodRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\ParameterRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\Types\ArrayTypeRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\Types\BuiltinTypeRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\Types\ClassTypeRenderer;
use DarkDarin\PhpEntityRenderer\Renderers\ValueRenderer;
use DarkDarin\XsdEntityGenerator\DTO\Attribute;
use DarkDarin\XsdEntityGenerator\DTO\BaseTypeEnum;
use DarkDarin\XsdEntityGenerator\DTO\ComplexType;
use DarkDarin\XsdEntityGenerator\DTO\Element;
use DarkDarin\XsdEntityGenerator\DTO\NamedTypeInterface;
use DarkDarin\XsdEntityGenerator\DTO\Schema;
use DarkDarin\XsdEntityGenerator\DTO\SimpleType;
use DarkDarin\XsdEntityGenerator\DTO\UseEnum;
use DarkDarin\XsdEntityGenerator\DTO\WithAnnotationInterface;
use Illuminate\Support\Str;
use Symfony\Component\Serializer\Attribute\SerializedName;

/**
 * @psalm-api
 */
class DTOGenerator
{
    /** @var array<string, NamedTypeInterface> */
    private array $globalTypes = [];
    /** @var array<string, TypeRendererInterface> */
    private array $resolvedTypes = [];
    private Context $globalContext;

    public function __construct(
        private readonly PrimitiveTypeResolverInterface $primitiveTypeResolver,
    ) {
        $this->globalContext = new Context('', '');
    }

    public function generate(Schema $schema, string $basePath, string $namespace): void
    {
        $this->globalContext = new Context($namespace, $basePath);

        foreach ($schema->simpleType as $simpleType) {
            if ($simpleType->name !== null) {
                $this->globalTypes[$simpleType->name] = $simpleType;
            }
        }
        foreach ($schema->complexType as $complexType) {
            if ($complexType->name !== null) {
                $this->globalTypes[$complexType->name] = $complexType;
            }
        }
        foreach ($this->globalTypes as $type) {
            $this->resolveGlobalType($type);
        }

        foreach ($schema->element as $element) {
            $this->getElementType($element, $this->globalContext);
        }
    }

    private function getGlobalType(BaseTypeEnum|string $type): TypeRendererInterface
    {
        if ($type instanceof BaseTypeEnum) {
            return $this->primitiveTypeResolver->resolveType($type->getPrimitiveType());
        }

        if (array_key_exists($type, $this->globalTypes)) {
            return $this->resolveGlobalType($this->globalTypes[$type]);
        }

        return BuiltinTypeRenderer::Mixed;
    }

    private function resolveGlobalType(NamedTypeInterface $type): TypeRendererInterface
    {
        if ($type->getName() !== null && array_key_exists($type->getName(), $this->resolvedTypes)) {
            return $this->resolvedTypes[$type->getName()];
        }

        if ($type instanceof SimpleType) {
            $typeRenderer = $this->getSimpleType($type, $this->globalContext);
        } elseif ($type instanceof ComplexType) {
            $typeRenderer = $this->getComplexType($type, $this->globalContext);
        } else {
            $typeRenderer = BuiltinTypeRenderer::Mixed;
        }

        if ($type->getName() !== null) {
            $this->resolvedTypes[$type->getName()] = $typeRenderer;
        }

        return $typeRenderer;
    }

    private function getSimpleType(
        SimpleType $type,
        Context $context,
        ?string $parentElementName = null
    ): TypeRendererInterface {
        if ($type->restriction !== null) {
            if ($type->restriction->enumeration !== null) {
                $className = $this->getEntityClassName($type, $parentElementName);
                return $this->generateEnum($type, $className, $context);
            }


            return $this->getGlobalType($type->restriction->base);
        }

        return BuiltinTypeRenderer::Mixed;
    }

    private function getComplexType(
        ComplexType $type,
        Context $context,
        ?string $parentElementName = null
    ): TypeRendererInterface {
        $className = $this->getEntityClassName($type, $parentElementName);

        return $this->generateClass($type, $className, $context);
    }

    private function getElementType(Element $element, Context $context): TypeRendererInterface
    {
        if ($element->type !== null) {
            return $this->getGlobalType($element->type);
        }

        if ($element->complexType !== null) {
            return $this->getComplexType($element->complexType, $context, $element->name);
        }

        if ($element->simpleType !== null) {
            return $this->getSimpleType($element->simpleType, $context, $element->name);
        }

        return BuiltinTypeRenderer::Mixed;
    }

    private function getAttributeType(Attribute $attribute, Context $context): TypeRendererInterface
    {
        if ($attribute->type !== null) {
            return $this->getGlobalType($attribute->type);
        }

        if ($attribute->simpleType !== null) {
            return $this->getSimpleType($attribute->simpleType, $context, $attribute->name);
        }

        return BuiltinTypeRenderer::Mixed;
    }

    private function generateClass(ComplexType $type, string $className, Context $context): TypeRendererInterface
    {
        $entityAliases = new EntityAliases();
        $classRenderer = new ClassRenderer($context->getClassName($className));

        $this->setDescriptionFromAnnotation($type, $classRenderer);

        $constructorRenderer = new MethodRenderer('__construct');
        $constructorRenderer->setVisibilityModifier(VisibilityModifierEnum::Public);

        $sequenceElements = $type->sequence?->element;
        if (is_array($sequenceElements)) {
            foreach ($sequenceElements as $element) {
                $parameterRenderer = $this->getRendererFromElement($element, $className, $context);
                $parameterRenderer->setVisibilityModifier(VisibilityModifierEnum::Public)
                    ->setReadonly();
                $constructorRenderer->addParameter($parameterRenderer);
            }
        }

        $choiceElements = $type->choice?->element;
        if (is_array($choiceElements)) {
            foreach ($choiceElements as $element) {
                $parameterRenderer = $this->getRendererFromElement($element, $className, $context);
                $parameterRenderer->setVisibilityModifier(VisibilityModifierEnum::Public)
                    ->setReadonly();
                $constructorRenderer->addParameter($parameterRenderer);
            }
        }

        $allElements = $type->all?->element;
        if (is_array($allElements)) {
            foreach ($allElements as $element) {
                $parameterRenderer = $this->getRendererFromElement($element, $className, $context);
                $parameterRenderer->setVisibilityModifier(VisibilityModifierEnum::Public)
                    ->setReadonly();
                $constructorRenderer->addParameter($parameterRenderer);
            }
        }

        if ($type->attribute !== null) {
            foreach ($type->attribute as $attribute) {
                if ($attribute->use === UseEnum::Prohibited) {
                    continue;
                }

                $parameterRenderer = $this->getRendererFromAttribute($attribute, $context);
                $parameterRenderer->setVisibilityModifier(VisibilityModifierEnum::Public)
                    ->setReadonly();
                $constructorRenderer->addParameter($parameterRenderer);
            }
        }

        $classRenderer->addMethod($constructorRenderer);
        $this->saveEntity($classRenderer, $className, $entityAliases, $context);

        return new ClassTypeRenderer($context->getClassName($className));
    }

    private function getRendererFromElement(Element $element, string $className, Context $context): ParameterRenderer
    {
        $parameterName = $this->getCorrectVariableName($element->name);
        $parameterType = $this->getElementType($element, $context->appendName($className));
        if ($element->maxOccurs !== 1) {
            $parameterType = new ArrayTypeRenderer($parameterType);
        } elseif ($element->minOccurs === 0) {
            $parameterType = $parameterType->setNullable();
        }

        $parameterRenderer = new ParameterRenderer($parameterName, $parameterType);
        $this->setDescriptionFromAnnotation($element, $parameterRenderer);

        if ($element->minOccurs === 0) {
            if ($parameterType instanceof ArrayTypeRenderer) {
                $parameterRenderer->setDefault(new ValueRenderer([]));
            } else {
                $parameterRenderer->setDefault(new ValueRenderer(null));
            }
        }
        if ($parameterName !== $element->name) {
            $parameterRenderer->addAttribute(
                new AttributeRenderer(SerializedName::class, [new ValueRenderer($element->name)])
            );
        }

        return $parameterRenderer;
    }

    private function getRendererFromAttribute(Attribute $attribute, Context $context): ParameterRenderer
    {
        $parameterName = $this->getCorrectVariableName($attribute->name);

        $parameterType = $this->getAttributeType($attribute, $context);
        if ($attribute->use === UseEnum::Optional || $attribute->default !== null) {
            $parameterType = $parameterType->setNullable();
        }

        $parameterRenderer = new ParameterRenderer($parameterName, $parameterType);
        $parameterRenderer->setVisibilityModifier(VisibilityModifierEnum::Public);
        $this->setDescriptionFromAnnotation($attribute, $parameterRenderer);

        if ($attribute->use === UseEnum::Optional || $attribute->default !== null) {
            $parameterRenderer->setDefault(new ValueRenderer(null));
        }

        $serializedNameAttribute = new AttributeRenderer(
            SerializedName::class,
            [new ValueRenderer('@' . $attribute->name)]
        );
        $parameterRenderer->addAttribute($serializedNameAttribute);

        return $parameterRenderer;
    }

    private function generateEnum(SimpleType $type, string $className, Context $context): TypeRendererInterface
    {
        $enumType = EnumTypeEnum::String;

        $enumerations = $type->restriction?->enumeration;
        $baseType = $type->restriction?->base;
        if (!is_array($enumerations)) {
            return $baseType !== null ? $this->getGlobalType($baseType) : BuiltinTypeRenderer::Mixed;
        }

        if ($baseType !== null) {
            $enumGlobalType = $this->getGlobalType($baseType);
            $enumType = match ($enumGlobalType) {
                BuiltinTypeRenderer::Int => EnumTypeEnum::Int,
                default => EnumTypeEnum::String,
            };
        }

        $entityAliases = new EntityAliases();
        $enumRenderer = new EnumRenderer($context->getClassName($className));
        $enumRenderer->setType($enumType);

        $this->setDescriptionFromAnnotation($type, $enumRenderer);

        foreach ($enumerations as $enumCase) {
            $caseName = $this->getCorrectVariableName($enumCase->value);
            $caseValue = new ValueRenderer($enumCase->value);
            $enumRenderer->addCase(new EnumCaseRenderer($caseName, $caseValue));
        }

        $this->saveEntity($enumRenderer, $className, $entityAliases, $context);

        return new ClassTypeRenderer($context->getClassName($className));
    }

    private function getEntityClassName(NamedTypeInterface $type, ?string $parentElementName = null): string
    {
        if ($type->getName() !== null) {
            return $this->getCorrectClassName($type->getName());
        } elseif ($parentElementName !== null) {
            return $this->getCorrectClassName($parentElementName);
        } else {
            return 'Entity';
        }
    }

    private function setDescriptionFromAnnotation(
        WithAnnotationInterface $annotation,
        EntityWithDescriptionInterface $entity
    ): void {
        $documents = $annotation->getAnnotation()?->documentation ?? [];
        if (count($documents) > 0 && $documents[0]->content !== null) {
            $entity->setDescription($documents[0]->content);
        }
    }

    private function getCorrectClassName(string $className): string
    {
        return Str::ucfirst($this->getCorrectVariableName($className));
    }

    private function getCorrectVariableName(string $variableName): string
    {
        $name = Str::transliterate($variableName);
        $name = preg_replace('/[^0-9a-z_]/iu', '_', $name);
        if (preg_match('/^[0-9]/iu', $name)) {
            $name = '_' . $name;
        }
        return $name;
    }

    private function saveEntity(
        EntityRendererInterface $renderer,
        string $className,
        EntityAliases $entityAliases,
        Context $context,
    ): void {
        $directory = $context->getPath();
        if (!file_exists($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new \RuntimeException('Can not create folder "' . $directory . '" to save class.');
        }

        file_put_contents(
            $context->getClassNamePath($className),
            $renderer->render($entityAliases)
        );
    }
}
