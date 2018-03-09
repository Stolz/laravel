<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // NOTE Insert here content of $this->crateNormalTable() or $this->createPivotTable() and then delete both functions along with $this->ordinaryColumns().
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('some_table');
    }

    // NOTE When you finish delete all functions below here, they are just boilerplate.

    /**
     * Sample skeleton for creating a normal table.
     *
     * @return void
     */
    public function crateNormalTable()
    {
        Schema::create('xxxs', function (Blueprint $table) {

            // Set the storage engine and primary key
            $table->engine = 'InnoDB';
            $table->increments('id');// NOTE Alternatives: tinyIncrements smallIncrements mediumIncrements bigIncrements

            // Ordinary columns
            // NOTE see $this->ordinaryColumns()

            // Foreign keys
            $table->unsignedInteger('zzz_id')->nullable();
            $table->foreign('zzz_id')->references('id')->on('zzzs')->onUpdate('cascade')->onDelete('cascade');

            // Extra keys
            $table->index('column', $name = null);
            $table->ordinaryColumn->unique(); // Create column and index at the same time
            $table->unique('column');  // Create index after creating the column
            $table->unique('column', 'custom_index_name');  // Create index after creating the column, with csutom index name
            $table->unique(['foo', 'bar']); // Multiple columns

            // Automatic columns
            $table->timestamps();// NOTE Alternative nullableTimestamps();
            $table->softDeletes();
        });
    }

    /**
     * Sample skeleton for creating a pivot table.
     *
     * @return void
     */
    public function createPivotTable()
    {
        Schema::create('xxx_yyy', function (Blueprint $table) {

            // Set the storage engine and primary key
            $table->engine = 'InnoDB';
            $table->increments('id');

            // Foreign keys
            $table->unsignedInteger('xxx_id');
            $table->foreign('xxx_id')->references('id')->on('xxxs')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('yyy_id');
            $table->foreign('yyy_id')->references('id')->on('yyys')->onUpdate('cascade')->onDelete('cascade');

            // Pivot columns
            // NOTE see $this->ordinaryColumns()

            // Extra keys
            $table->unique(['xxx_id', 'yyy_id']);

            // Automatic columns
            $table->timestamps();
        });
    }

    /**
     * Sample for adding ordinary columns.
     *
     * @return void
     */
    public function ordinaryColumns()
    {
        /* Modifiers:
        ->after('column')         Place the column "after" another column (MySQL)
        ->autoIncrement()         Set INTEGER columns as auto-increment (primary key)
        ->charset('utf8')         Specify a character set for the column (MySQL)
        ->collation('utf8_unicode_ci') Specify a collation for the column (MySQL/SQL Server)
        ->comment('my comment')   Add a comment to a column (MySQL)
        ->default($value)         Specify a "default" value for the column
        ->first()                 Place the column "first" in the table (MySQL)
        ->nullable($value = true) Allows (by default) NULL values to be inserted into the column
        ->storedAs($expression)   Create a stored generated column (MySQL)
        ->unsigned()              Set INTEGER columns as UNSIGNED (MySQL)
        ->useCurrent()            Set TIMESTAMP columns to use CURRENT_TIMESTAMP as default value
        ->virtualAs($expression)  Create a virtual generated column (MySQL)
        */

        // Integers (Exact value)
        $table->tinyInteger('column_name');
        $table->smallInteger('column_name');
        $table->mediumInteger('column_name');
        $table->integer('column_name');
        $table->bigInteger('column_name');

        // Unsigned integers (Exact value)
        $table->unsignedTinyInteger('votes');
        $table->unsignedSmallInteger('votes');
        $table->unsignedMediumInteger('votes');
        $table->unsignedInteger('column_name');
        $table->unsignedBigInteger('column_name');

        // Fixed-point numbers (Exact value)
        $table->decimal('column_name', $totalDigits = 8, $decimalDigits = 2);
        $table->unsignedDecimal('column_name', $totalDigits = 8, $decimalDigits = 2);

        // Floating-point numbers (Approximate value)
        $table->float('column_name', $totalDigits = 8, $decimalDigits = 2);
        $table->double('column_name', $totalDigits = null, $decimalDigits = null);

        // Text
        $table->char('column_name', $length = 255);
        $table->string('column_name', $length = 255);
        $table->text('column_name');
        $table->mediumText('column_name');
        $table->longText('column_name');

        // Date and Time
        $table->time('column_name');
        $table->timeTz('sunrise');
        $table->timestamp('column_name');
        $table->timestampTz('added_on');
        $table->date('column_name');
        $table->dateTime('column_name');
        $table->dateTimeTz('column_name');
        $table->year('birth_year');

        // JSON
        $table->json('options');
        $table->jsonb('options');
    }

    /* Other available methods pendding to add to this template:
    $table->binary('data'); BLOB equivalent column.
    $table->boolean('confirmed');   BOOLEAN equivalent for the database.
    $table->enum('choices', ['foo', 'bar']);   ENUM equivalent for the database.
    $table->geometry('positions');  GEOMETRY equivalent column.
    $table->geometryCollection('positions'); GEOMETRYCOLLECTION equivalent column.
    $table->ipAddress('visitor'); IP address equivalent column.
    $table->lineString('positions'); LINESTRING equivalent column.
    $table->macAddress('device'); MAC address equivalent column.
    $table->morphs('taggable');   Adds INTEGER taggable_id and STRING taggable_type
    $table->multiLineString('positions'); MULTILINESTRING equivalent column.
    $table->multiPoint('positions'); MULTIPOINT equivalent column.
    $table->multiPolygon('positions'); MULTIPOLYGON equivalent column.
    $table->nullableMorphs('taggable'); Adds nullable versions of morphs() columns.
    $table->nullableTimestamps(); Alias of timestamps() method.
    $table->point('position'); POINT equivalent column.
    $table->polygon('positions'); POLYGON equivalent column.
    $table->primary('string|array');
    $table->rememberToken();   Adds remember_token as VARCHAR(100) NULL
    $table->softDeletes(); Adds a nullable deleted_at TIMESTAMP equivalent column for soft deletes.
    $table->softDeletesTz(); Adds a nullable deleted_at TIMESTAMP (with timezone) equivalent column for soft deletes.
    $table->timestamps(); Adds nullable created_at and updated_at TIMESTAMP equivalent columns.
    $table->timestampsTz(); Adds nullable created_at and updated_at TIMESTAMP (with timezone) equivalent columns.
    $table->uuid('id'); UUID equivalent column.

    $table->dropColumn('column');
    $table->dropForeign($index);
    $table->dropIndex($index);
    $table->dropPrimary($index = null)
    $table->dropSoftDeletes();
    $table->dropTimestamps();
    $table->dropUnique($index);
    $table->getColumns();
    $table->getCommands();
    $table->getTable();
    $table->removeColumn($name);
    $table->rename($to);
    $table->renameColumn($from, $to);
    $table->toSql(Connection $connection, Grammar $grammar);
     */
}
