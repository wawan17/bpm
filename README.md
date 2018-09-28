# README

![ProcessMaker](http://www.processmaker.com/themes/processmaker/images/logo.jpg)

[![CircleCI](https://circleci.com/gh/ProcessMaker/bpm/tree/develop.svg?style=svg&circle-token=bc15deff649712440252088a12ec20b4b7c96826)](https://circleci.com/gh/ProcessMaker/bpm/tree/develop)

## README

### ProcessMaker v4.0

### Overview

ProcessMaker is an open source, workflow management software suite, which includes tools to automate your workflow, design forms, create documents, assign roles and users, create routing rules, and map an individual process quickly and easily. It's relatively lightweight and doesn't require any kind of installation on the client computer. This file describes the requirements and installation steps for the server.

## Contributing

When developing, make sure to turn on debugging in your `.env` so you can see the
actual error instead of the Whoops page.
```
APP_DEBUG=TRUE
```

### API

The ProcessMaker API is documented using OpenAPI 3.0 documentation and can be viewed at
`/api/documentation`. The documention is generated by adding annotations to Models and
Controllers.

All api endpoints should be well annotated because it's how we generate the SDKs
that are used when running scripts.

When developing, make sure to add this to your `.env` file so that any changes
you make to the annotations are automatically turned into documentation when you
reload the `/api/documentation` page:

```
L5_SWAGGER_GENERATE_ALWAYS=TRUE
```

At the comment block at the top of the model, add an @OA annotation to describe
the schema. See `ProcessMaker/Models/Process.php` for an example
```php
/**
 * ...existing comments above...
 * 
 * @OA\Schema(
 *   schema="Process",
 *   @OA\Property(property="uuid", type="string"),
 *   @OA\Property(property="process_category_uuid", type="string"),
 *   @OA\Property(property="user_uuid", type="string"),
 *   @OA\Property(property="bpmn", type="string"),
 *   @OA\Property(property="name", type="string"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="status", type="string", enum={"ACTIVE", "INACTIVE"})
 * )
 */
class Process extends Model implements HasMedia
{
...
```
Now you can use the reference to the schema when annotating the controllers. See
`ProcessMaker/Http/Controllers/Api/ProcessController.php` for an example.
```php
    /**
     * @OA\Get(
     *     path="/processes",
     *     summary="Returns all processes that the user has access to",
     *     operationId="getProcesses",
     *     tags={"Process"},
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         description="Filter results with a string. Searches Name, Description, and Status",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="list of processes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Process")
     *         ),
     *     ),
     * )
     */
    public function index(Request $request)
    {
    ...
```

And for a show method

```php
    /**
     * @OA\Get(
     *     path="/process/{processUuid}",
     *     summary="Get single process by ID",
     *     operationId="getProcessByUuid",
     *     tags={"Process"},
     *     @OA\Parameter(
     *         description="ID of pet to return",
     *         in="path",
     *         name="processUuid",
     *         required=true,
     *         @OA\Schema(
     *           type="string",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully found the process",
     *         @OA\JsonContent(ref="#/components/schemas/Process")
     *     ),
     * )
     */
    public function show(Request $request, Process $process)
    {
    ...
```

### Testing with Swagger UI

Reload the swagger UI at `api/documentation` page in your browser to see the results and 
debug any errors with the annotations.

All api requests are authenticated with a personal access token. You can
generate one with php artisan tinker like so:

```
$ php artisan tinker
>>> User::first()->createToken('api')->accessToken
```
Copy the resulting token. Then in the swagger UI click on Authorize and paste the token.
Authorize and close. You should now be able to use the "Try it out" functionality.

### More Info

Detailed examples can be found at https://github.com/zircote/swagger-php/tree/master/Examples/petstore.swagger.io

Full OpenAPI 3.0 specification at https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.0.md


### License

ProcessMaker - Automate your Workflow Copyright \(C\) 2002 - 2018 ProcessMaker Inc.

For further information visit: [http://www.processmaker.com/](http://www.processmaker.com/)

