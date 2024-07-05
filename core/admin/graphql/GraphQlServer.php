<?php

    namespace Horizon\Core\Admin\GraphQL;

    use GraphQL\GraphQL;
    use GraphQL\Error\DebugFlag;
    use Horizon\Core\Env\EnvLoader;

    class GraphQLServer {
        public static function handle() {
            try {
                EnvLoader::load(__DIR__ . '/../../../..');
                $schema = SchemaBuilder::build();
                $rawInput = file_get_contents('php://input');
                $input = json_decode($rawInput, true);
                $query = $input['query'];
                $variableValues = isset($input['variables']) ? $input['variables'] : null;

                $rootValue = [];
                $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
                $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE);

            } catch (\Exception $e) {
                $output = [
                    'errors' => [
                        [
                            'message' => $e->getMessage()
                        ]
                    ]
                ];
            }

            header('Content-Type: application/json');
            echo json_encode($output);
        }
    }
