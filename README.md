# xsd-entity-generator
Generator for make DTO from XSD

# Install

```bash
composer require darkdarin/xsd-entity-generator
```

# Usage

```php
use DarkDarin\XsdEntityGenerator\SchemaLoader;
use DarkDarin\XsdEntityGenerator\Serializer\SchemaSerializerFactory;
use DarkDarin\XsdEntityGenerator\DTOGenerator;
use DarkDarin\XsdEntityGenerator\PrimitiveTypeResolver;

$schemaSerializer = (new SchemaSerializerFactory())();
$schemaLoader = new SchemaLoader($schemaSerializer);
$schema = $schemaLoader->load('path/to/schema.xsd');

$dtoGenerator = new DTOGenerator(new PrimitiveTypeResolver());
$dtoGenerator->generate($schema, 'path/to/generated/classes', '\\Namespace\\For\\Generated\\Classes');
```
