<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('continents', function (Blueprint $table) {
            $table->increments('id');
            $table->json('name');
            $table->char('code', 2)->unique();
            $table->json('alias')->nullable();
            $table->json('abbr')->nullable();
            $table->json('full_name')->nullable();
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('continent_id')->comment('Continent ID');
            $table->json('name');
            $table->json('alias')->nullable();
            $table->json('abbr')->nullable();
            $table->json('full_name')->nullable();
            $table->json('capital')->nullable();
            $table->char('code', 4)->unique()->comment('ISO3166-1-Alpha-2');
            $table->char('code_alpha3', 6)->unique()->comment('ISO3166-1-Alpha-3');
            $table->string('emoji', 16)->nullable()->comment('Country Emoji');
            $table->boolean('has_state')->default(false)->index();
            $table->boolean('status')->default(false);
            $table->char('currency', 3)->nullable()->comment('iso_4217_code');
            $table->json('currency_name')->nullable()->comment('iso_4217_name');
            $table->string('tld', 8)->nullable()->comment('Top level domain');
            $table->string('callingcode', 8)->nullable()->comment('Calling prefix');
        });

        Schema::create('states', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id');
            $table->json('name');
            $table->json('alias')->nullable();
            $table->json('abbr')->nullable();
            $table->json('full_name')->nullable();
            $table->char('code', 10)->nullable()->index();
            $table->boolean('has_city')->default(false);
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id')->index();
            $table->unsignedInteger('state_id')->nullable()->index();
            $table->json('name');
            $table->json('alias')->nullable();
            $table->json('abbr')->nullable();
            $table->json('full_name')->nullable();
            $table->char('code', 10)->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cities');
        Schema::drop('states');
        Schema::drop('countries');
        Schema::drop('continents');
    }
}
