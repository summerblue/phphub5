<?php

return array(

    // Datatype for primary keys of your models.
    // used in migrations only
    'primary_keys_type' => 'integer', // 'string' or 'integer'

    // Value of are passed through this before save of tags
    'normalizer' => '\EstGroupe\Taggable\Util::slug',

    // Display value of tags are passed through (for front end display)
    'displayer' => '\EstGroupe\Taggable\Util::tagName',

    // Database connection for Conner\Taggable\Tag model to use
// 	'connection' => 'mysql',

    // When deleting a model, remove all the tags relationship
    'untag_on_delete' => true,

    // Auto-delete unused tags from the 'tags' database table,
    // when untaged, and they are used zero times.
    'delete_unused_tags'=> false,

    // Model to use to store the tags in the database.
    // You can create your own and inherit the Taggable Tag.
    'tag_model'=> '\App\Model\Tag',

    // Whether wan to keep track of the Model is tagged or not.
    // You have to set the field in your model like:
    // $table->enum('is_tagged', array('yes', 'no'))->default('no');
    // Then you can call: Article::where('is_tagged', 'yes')->get()
    // to get all tagged $articles.
    'is_tagged_label_enable' => false,

    // customize table name
    'tags_table_name'      => 'tags',
    'taggables_table_name' => 'taggables',

);
