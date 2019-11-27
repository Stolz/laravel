<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // By defaul Oracle applies ON DELETE RESTRICT to all foreign keys (although they named it as NO ACTION)
        // but if we explicitly use it when creating the table it will fail. In order to keep the migration
        // compatible with other database systems supported by Laravel we use a null value for Oracle only
        $restrict = db_is('oracle') ? null : 'restrict';

        Schema::create('users', function (Blueprint $table) use ($restrict) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('timezone', 128)->default('UTC');
            $table->unsignedInteger('role_id')->index(/*Not required for MySQL*/);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade')->onDelete($restrict);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
