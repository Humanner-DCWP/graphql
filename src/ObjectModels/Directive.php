<?php

declare(strict_types=1);

namespace PoP\GraphQL\ObjectModels;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\GraphQL\ObjectModels\DirectiveLocations;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\GraphQL\ObjectModels\HasArgsSchemaDefinitionReferenceTrait;

class Directive extends AbstractSchemaDefinitionReferenceObject
{
    use HasArgsSchemaDefinitionReferenceTrait;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);

        $this->initArgs($fullSchemaDefinition, $schemaDefinitionPath);
        $this->initializeArgsTypeDependencies();
    }
    public function getName(): string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_NAME];
    }
    public function getDescription(): ?string
    {
        return $this->schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION];
    }
    public function getLocations(): array
    {
        $directives = [];
        $directiveType = $this->schemaDefinition[SchemaDefinition::ARGNAME_DIRECTIVE_TYPE];
        $vars = ApplicationState::getVars();
        /**
         * There are 2 cases for adding the "Query" type locations:
         * 1. When the type is "Query"
         * 2. When the type is "Schema" and we are editing the query on the back-end (as to replace the lack of SDL)
         */
        if ($directiveType == DirectiveTypes::QUERY || ($directiveType == DirectiveTypes::SCHEMA && $vars['edit-schema'])) {
            // Same DirectiveLocations as used by "@skip": https://graphql.github.io/graphql-spec/draft/#sec--skip
            $directives = array_merge(
                $directives,
                [
                    DirectiveLocations::FIELD,
                    DirectiveLocations::FRAGMENT_SPREAD,
                    DirectiveLocations::INLINE_FRAGMENT,
                ]
            );
        }
        if ($directiveType == DirectiveTypes::SCHEMA) {
            $directives = array_merge(
                $directives,
                [
                    DirectiveLocations::FIELD_DEFINITION,
                ]
            );
        }
        return $directives;
    }
}
