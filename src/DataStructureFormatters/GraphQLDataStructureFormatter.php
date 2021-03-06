<?php

declare(strict_types=1);

namespace PoP\GraphQL\DataStructureFormatters;

use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Feedback\Tokens;

class GraphQLDataStructureFormatter extends \PoP\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter
{
    /**
     * Change properties for GraphQL
     *
     * @param string $dbKey
     * @param [type] $id
     * @param array $item
     * @return array
     */
    protected function getDBEntryExtensions(string $dbKey, $id, array $item): array
    {
        $vars = ApplicationState::getVars();
        if ($vars['standard-graphql']) {
            return [
                'type' => $dbKey,
                'id' => $id,
                'path' => $item[Tokens::PATH],
            ];
        }
        return parent::getDBEntryExtensions($dbKey, $id, $item);
    }

    /**
     * Change properties for GraphQL
     *
     * @param string $dbKey
     * @param array $item
     * @return array
     */
    protected function getSchemaEntryExtensions(string $dbKey, array $item): array
    {
        $vars = ApplicationState::getVars();
        if ($vars['standard-graphql']) {
            return [
                'type' => $dbKey,
                'path' => $item[Tokens::PATH],
            ];
        }
        return parent::getSchemaEntryExtensions($dbKey, $item);
    }
    /**
     * Override the parent function, to place the locations from outside extensions
     *
     * @param string $message
     * @param array $extensions
     * @return void
     */
    protected function getQueryEntry(string $message, array $extensions): array
    {
        $entry = [
            'message' => $message,
        ];
        // Add the "location" directly, not under "extensions"
        if ($location = $extensions['location']) {
            unset($extensions['location']);
            $entry['location'] = $location;
        }
        if ($extensions = array_merge(
            $this->getQueryEntryExtensions(),
            $extensions
        )) {
            $entry['extensions'] = $extensions;
        };
        return $entry;
    }

    /**
     * Change properties for GraphQL
     *
     * @return array
     */
    protected function getQueryEntryExtensions(): array
    {
        $vars = ApplicationState::getVars();
        if ($vars['standard-graphql']) {
            // Do not print "type" => "query"
            return [];
        }
        return parent::getQueryEntryExtensions();
    }
}
