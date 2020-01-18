<?php
namespace PoP\GraphQL\ObjectModels;

use PoP\GraphQL\ObjectModels\AbstractType;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\GraphQL\ObjectModels\AbstractSchemaDefinitionReferenceObject;
use PoP\GraphQL\ObjectModels\HasLazyTypeSchemaDefinitionReferenceTrait;

class InputValue extends AbstractSchemaDefinitionReferenceObject
{
    use HasLazyTypeSchemaDefinitionReferenceTrait;

    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION];
    }
    /**
     * Undocumented function
     *
     * @return string|null
     */
    public function getDefaultValue(): ?string
    {
        // The default value must be returned as a JSON encoded string
        // From the GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-IAJbTHHAABABM7kV):
        // "defaultValue may return a String encoding (using the GraphQL language) of the default value used by this input value in the condition a value is not provided at runtime. If this input value has no default value, returns null."
        if ($defaultValue = $this->schemaDefinition[SchemaDefinition::ARGNAME_DEFAULT_VALUE]) {
            return json_encode($defaultValue);
        }
        return null;
    }
}
