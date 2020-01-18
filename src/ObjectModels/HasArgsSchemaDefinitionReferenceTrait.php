<?php
namespace PoP\GraphQL\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use PoP\GraphQL\ObjectModels\InputValue;

trait HasArgsSchemaDefinitionReferenceTrait
{
    protected $args;
    protected function initArgs(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->args = [];
        if ($args = $this->schemaDefinition[SchemaDefinition::ARGNAME_ARGS]) {
            foreach (array_keys($args) as $fieldArgName) {
                $fieldArgSchemaDefinitionPath = array_merge(
                    $schemaDefinitionPath,
                    [
                        SchemaDefinition::ARGNAME_ARGS,
                        $fieldArgName,
                    ]
                );
                $this->args[] = new InputValue(
                    $fullSchemaDefinition,
                    $fieldArgSchemaDefinitionPath
                );
            }
        }
    }
    /**
     * Implementation of "args" field from the Field object (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLACsEIDuEAA-vb)
     *
     * @return array of InputValue type
     */
    public function getArgs(): array
    {
        return $this->args;
    }
    public function getArgIDs(): array
    {
        return array_map(
            function(InputValue $inputValue) {
                return $inputValue->getID();
            },
            $this->getArgs()
        );
    }
}