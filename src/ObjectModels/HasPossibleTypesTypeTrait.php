<?php

declare(strict_types=1);

namespace PoP\GraphQL\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use PoP\GraphQL\ObjectModels\ResolveTypeSchemaDefinitionReferenceTrait;

trait HasPossibleTypesTypeTrait
{
    use ResolveTypeSchemaDefinitionReferenceTrait;

    protected $possibleTypes;
    public function getPossibleTypes(): array
    {
        return $this->possibleTypes;
    }
    /**
     * Obtain the reference to the type from the registryMap
     *
     * @return void
     */
    protected function initPossibleTypes(): void
    {
        // Create a reference to the type in the referenceMap. Either it has already been created, or will be created later on
        // It is done this way because from the Schema we initialize the Types, each of which initializes their Fields (we are here), which may reference a different Type that doesn't exist yet, and can't be created here or it creates an endless loop
        $this->possibleTypes = [];
        foreach ($this->schemaDefinition[SchemaDefinition::ARGNAME_POSSIBLE_TYPES] as $typeName) {
            $this->possibleTypes[] = $this->getTypeFromTypeName($typeName);
        }
    }
    public function getPossibleTypeIDs(): array
    {
        return array_map(
            function (AbstractType $type) {
                return $type->getID();
            },
            $this->getPossibleTypes()
        );
    }
}
