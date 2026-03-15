<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Unitask API',
    description: 'Documentation for Unitask API - a task management system built with Laravel',
)]
#[OA\Server(
    url: 'https://api.unitask.ankocreative.com',
    description: 'Development server'
)]
#[OA\Tag(name: 'Auth', description: 'Authentication endpoints')]
#[OA\Tag(name: 'Home', description: 'Home dashboard endpoint')]
#[OA\Tag(name: 'Teams', description: 'Team management')]
#[OA\Tag(name: 'Projects', description: 'Project management')]
#[OA\Tag(name: 'Tasks', description: 'Task management')]
#[OA\Tag(name: 'Members', description: 'Team members management')]
#[OA\Tag(name: 'Task Comments', description: 'Task comments')]
#[OA\Tag(name: 'Task Attachments', description: 'Task attachments')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Sanctum personal access token'
)]
#[OA\Schema(
    schema: 'UserResource',
    type: 'object',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Gabriel'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
    ]
)]
#[OA\Schema(
    schema: 'TeamResource',
    type: 'object',
    properties: [
        new OA\Property(property: 'slug', type: 'string', example: 'design-team'),
        new OA\Property(property: 'name', type: 'string', example: 'Design Team'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Internal design team'),
    ]
)]
#[OA\Schema(
    schema: 'Project',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 10),
        new OA\Property(property: 'name', type: 'string', example: 'Website Redesign'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Q2 project'),
        new OA\Property(property: 'team_id', type: 'integer', example: 2),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'TaskResource',
    type: 'object',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Implement API docs'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Fill Swagger spec'),
        new OA\Property(property: 'status', type: 'string', nullable: true, example: 'in_progress'),
    ]
)]
#[OA\Schema(
    schema: 'TaskComment',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'task_id', type: 'integer', example: 7),
        new OA\Property(property: 'user_id', type: 'integer', example: 3),
        new OA\Property(property: 'comment', type: 'string', example: 'Needs review'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'TaskAttachment',
    type: 'object',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'task_id', type: 'integer', example: 7),
        new OA\Property(property: 'filename', type: 'string', example: 'brief.pdf'),
        new OA\Property(property: 'filepath', type: 'string', example: 'uploads/brief.pdf'),
        new OA\Property(property: 'filetype', type: 'string', nullable: true, example: 'application/pdf'),
        new OA\Property(property: 'filesize', type: 'integer', nullable: true, example: 204800),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[OA\Schema(
    schema: 'ErrorMessage',
    type: 'object',
    properties: [
        new OA\Property(property: 'message', type: 'string', example: 'Forbidden'),
    ]
)]
final class OpenApiSpec
{
    #[OA\Post(
        path: '/api/login',
        tags: ['Auth'],
        summary: 'Authenticate user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'secret123'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Invalid credentials', content: new OA\JsonContent(ref: '#/components/schemas/ErrorMessage')),
        ]
    )]
    public function authLogin(): void
    {
    }

    #[OA\Post(
        path: '/api/register',
        tags: ['Auth'],
        summary: 'Register user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'email', 'password', 'password_confirmation'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Gabriel'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'gabriel@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 6),
                    new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', minLength: 6),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string'),
                        new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function authRegister(): void
    {
    }

    #[OA\Post(
        path: '/api/logout',
        tags: ['Auth'],
        summary: 'Logout current user',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Logged out'),
            new OA\Response(response: 401, description: 'Unauthenticated', content: new OA\JsonContent(ref: '#/components/schemas/ErrorMessage')),
        ]
    )]
    public function authLogout(): void
    {
    }

    #[OA\Get(
        path: '/api/user',
        tags: ['Auth'],
        summary: 'Get authenticated user',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Current user', content: new OA\JsonContent(ref: '#/components/schemas/UserResource')),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function authUser(): void
    {
    }

    #[OA\Get(
        path: '/api/home',
        tags: ['Home'],
        summary: 'Home dashboard data',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Dashboard data',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'user', ref: '#/components/schemas/UserResource'),
                                new OA\Property(property: 'tasks', type: 'array', items: new OA\Items(ref: '#/components/schemas/TaskResource')),
                                new OA\Property(property: 'teams', type: 'array', items: new OA\Items(ref: '#/components/schemas/TeamResource')),
                                new OA\Property(property: 'team_count', type: 'integer', example: 3),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function home(): void
    {
    }

    #[OA\Get(
        path: '/api/team',
        tags: ['Teams'],
        summary: 'List authenticated user teams',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Team list', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/TeamResource'))),
        ]
    )]
    public function teamsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/team',
        tags: ['Teams'],
        summary: 'Create team',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Backend Team'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Team created', content: new OA\JsonContent(ref: '#/components/schemas/TeamResource')),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function teamsStore(): void
    {
    }

    #[OA\Get(
        path: '/api/team/{team}',
        tags: ['Teams'],
        summary: 'Show team',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'team', description: 'Team slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Team details', content: new OA\JsonContent(ref: '#/components/schemas/TeamResource')),
            new OA\Response(response: 403, description: 'Forbidden', content: new OA\JsonContent(ref: '#/components/schemas/ErrorMessage')),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function teamsShow(): void
    {
    }

    #[OA\Put(
        path: '/api/team/{team}',
        tags: ['Teams'],
        summary: 'Update team',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'team', description: 'Team slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Backend Team'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Team updated', content: new OA\JsonContent(ref: '#/components/schemas/TeamResource')),
            new OA\Response(response: 403, description: 'Forbidden', content: new OA\JsonContent(ref: '#/components/schemas/ErrorMessage')),
        ]
    )]
    #[OA\Patch(
        path: '/api/team/{team}',
        tags: ['Teams'],
        summary: 'Partially update team',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'team', description: 'Team slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [new OA\Property(property: 'name', type: 'string', maxLength: 255)])),
        responses: [
            new OA\Response(response: 200, description: 'Team updated', content: new OA\JsonContent(ref: '#/components/schemas/TeamResource')),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function teamsUpdate(): void
    {
    }

    #[OA\Delete(
        path: '/api/team/{team}',
        tags: ['Teams'],
        summary: 'Delete team',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'team', description: 'Team slug', in: 'path', required: true, schema: new OA\Schema(type: 'string')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Team deleted'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function teamsDestroy(): void
    {
    }

    #[OA\Post(
        path: '/api/member',
        tags: ['Members'],
        summary: 'Add a user to a team',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['user_id', 'team_id'],
                properties: [
                    new OA\Property(property: 'user_id', type: 'integer', example: 5),
                    new OA\Property(property: 'team_id', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'User added to team'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function membersStore(): void
    {
    }

    #[OA\Delete(
        path: '/api/member/{member}',
        tags: ['Members'],
        summary: 'Remove authenticated user from team context',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'member', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Removed from team'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function membersDestroy(): void
    {
    }

    #[OA\Get(
        path: '/api/projects',
        tags: ['Projects'],
        summary: 'List projects from user teams',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Project list', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Project'))),
        ]
    )]
    public function projectsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/projects',
        tags: ['Projects'],
        summary: 'Create project',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'team_id'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Mobile App'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Q3 roadmap'),
                    new OA\Property(property: 'team_id', type: 'integer', example: 2),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Project created', content: new OA\JsonContent(ref: '#/components/schemas/Project')),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function projectsStore(): void
    {
    }

    #[OA\Get(
        path: '/api/projects/{project}',
        tags: ['Projects'],
        summary: 'Show project',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Project details', content: new OA\JsonContent(ref: '#/components/schemas/Project')),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function projectsShow(): void
    {
    }

    #[OA\Put(
        path: '/api/projects/{project}',
        tags: ['Projects'],
        summary: 'Update project',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Project updated', content: new OA\JsonContent(ref: '#/components/schemas/Project')),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    #[OA\Patch(
        path: '/api/projects/{project}',
        tags: ['Projects'],
        summary: 'Partially update project',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [new OA\Property(property: 'name', type: 'string'), new OA\Property(property: 'description', type: 'string', nullable: true)])),
        responses: [new OA\Response(response: 200, description: 'Project updated')]
    )]
    public function projectsUpdate(): void
    {
    }

    #[OA\Delete(
        path: '/api/projects/{project}',
        tags: ['Projects'],
        summary: 'Delete project',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'project', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 204, description: 'Project deleted'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function projectsDestroy(): void
    {
    }

    #[OA\Get(
        path: '/api/tasks',
        summary: 'List user tasks',
        tags: ['Tasks'],
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Task list', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/TaskResource'))),
        ]
    )]
    public function tasksIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/tasks',
        tags: ['Tasks'],
        summary: 'Create task',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Review PR #42'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'team_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'assigned_user_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Task created', content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function tasksStore(): void
    {
    }

    #[OA\Get(
        path: '/api/tasks/{task}',
        tags: ['Tasks'],
        summary: 'Show task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Task details', content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function tasksShow(): void
    {
    }

    #[OA\Put(
        path: '/api/tasks/{task}',
        tags: ['Tasks'],
        summary: 'Update task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'title', type: 'string', maxLength: 255),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'team_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'assigned_user_id', type: 'integer', nullable: true),
                    new OA\Property(property: 'due_date', type: 'string', format: 'date', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Task updated', content: new OA\JsonContent(ref: '#/components/schemas/TaskResource')),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    #[OA\Patch(
        path: '/api/tasks/{task}',
        tags: ['Tasks'],
        summary: 'Partially update task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(content: new OA\JsonContent(properties: [new OA\Property(property: 'title', type: 'string'), new OA\Property(property: 'description', type: 'string', nullable: true)])),
        responses: [new OA\Response(response: 200, description: 'Task updated')]
    )]
    public function tasksUpdate(): void
    {
    }

    #[OA\Delete(
        path: '/api/tasks/{task}',
        tags: ['Tasks'],
        summary: 'Delete task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 204, description: 'Task deleted'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function tasksDestroy(): void
    {
    }

    #[OA\Get(
        path: '/api/tasks/{task}/comments',
        tags: ['Task Comments'],
        summary: 'List comments for task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Comment list', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/TaskComment'))),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function commentsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/tasks/{task}/comments',
        tags: ['Task Comments'],
        summary: 'Create comment for task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['comment'],
                properties: [new OA\Property(property: 'comment', type: 'string', example: 'Looks good')]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Comment created', content: new OA\JsonContent(ref: '#/components/schemas/TaskComment')),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function commentsStore(): void
    {
    }

    #[OA\Put(
        path: '/api/tasks/{task}/comments/{comment}',
        tags: ['Task Comments'],
        summary: 'Update comment',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'comment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['comment'], properties: [new OA\Property(property: 'comment', type: 'string')])
        ),
        responses: [
            new OA\Response(response: 200, description: 'Comment updated', content: new OA\JsonContent(ref: '#/components/schemas/TaskComment')),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function commentsUpdate(): void
    {
    }

    #[OA\Delete(
        path: '/api/tasks/{task}/comments/{comment}',
        tags: ['Task Comments'],
        summary: 'Delete comment',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'comment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Comment deleted'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function commentsDestroy(): void
    {
    }

    #[OA\Get(
        path: '/api/tasks/{task}/attachments',
        tags: ['Task Attachments'],
        summary: 'List attachments for task',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Attachment list', content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/TaskAttachment'))),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function attachmentsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/tasks/{task}/attachments',
        tags: ['Task Attachments'],
        summary: 'Create task attachment metadata',
        security: [['sanctum' => []]],
        parameters: [new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['filename', 'filepath'],
                properties: [
                    new OA\Property(property: 'filename', type: 'string', maxLength: 255, example: 'brief.pdf'),
                    new OA\Property(property: 'filepath', type: 'string', maxLength: 1024, example: 'uploads/brief.pdf'),
                    new OA\Property(property: 'filetype', type: 'string', nullable: true, example: 'application/pdf'),
                    new OA\Property(property: 'filesize', type: 'integer', nullable: true, minimum: 0, example: 90012),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Attachment created', content: new OA\JsonContent(ref: '#/components/schemas/TaskAttachment')),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function attachmentsStore(): void
    {
    }

    #[OA\Delete(
        path: '/api/tasks/{task}/attachments/{attachment}',
        tags: ['Task Attachments'],
        summary: 'Delete attachment',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'task', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'attachment', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Attachment deleted'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function attachmentsDestroy(): void
    {
    }
}
