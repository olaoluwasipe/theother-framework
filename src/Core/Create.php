<?php
$basePath = __DIR__ . '/../../'; // Adjust as needed for your structure
$tableName = '';
$className = '';

$directories = [
    'migration' => 'database/migrations/',
    'controller' => 'app/Controllers/',
    'model' => 'app/Models/',
];

// Check if type is valid
if (!array_key_exists($type, $directories)) {
    echo "Error: Invalid type '$type'. Valid types are: migration, controller, model.\n";
    exit(1);
}

$directory = $basePath . $directories[$type];
$fileName = ($type !== 'migration' ? ucfirst($name) : $name) . ($type === 'migration' ? '_' . date('Y_m_d_His') : '') . '.php';
$filePath = $directory . $fileName;

if($type === 'migration') {
    $tableName = str_replace('create_', '', $name);
    $tableName = str_replace('_table', '', $tableName);
    $className = explode('_', $name);
    $className = array_map('ucfirst', $className);
    $className = implode('', $className);
    $className .= 'Table';
}

// Ensure directory exists
if (!is_dir($directory)) {
    mkdir($directory, 0755, true);
}

// Define templates
$templates = [
    'migration' => <<<PHP
<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class $name {
    public function up(){
        Capsule::schema()->create('$tableName', function (Blueprint \$table) {
            \$table->id();
            \$table->timestamps();
        });
    }

    public function down() {
        Capsule::schema()->dropIfExists('$tableName');
    }
}
PHP,
    'controller' => <<<PHP
<?php

namespace App\Controllers;

use Core\Controller;
use Exception;

class {$name}Controller extends Controller
{
    public function index()
    {
        // Code for index method
    }

    public function show(\$id)
    {
        // Code for show method
    }
}
PHP,
    'model' => <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class $name extends Model
{
    protected \$table = 'table_name';
    protected \$fillable = ['column1', 'column2'];
}
PHP,
];

// Generate the file
if (file_put_contents($filePath, $templates[$type])) {
    echo ucfirst($type) . " created successfully: $filePath\n";
} else {
    echo "Error: Failed to create $type.\n";
}
