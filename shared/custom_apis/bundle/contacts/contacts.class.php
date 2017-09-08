<?php

class contacts extends bundleBaseObject {

    const tableName = 'capture_contacts_form';

    protected
        $name,
        $company,
        $phone,
        $email,
        $subject,
        $text,
        $author_ip,
        $add_date
    ;

    protected $fieldsDefinition = array(
        'name' => array(
            'not_empty' => true
        ),
        'subject' => array(
            'not_empty' => true
        ),

        'company' => array(
            'not_empty' => true
        ),
        'phone' => array(
        ),
        'email' => array(
            'not_empty' => true,
            'type' => 'email',
        ),

        'text' => array(
            'not_empty' => true
        ),


    );

    protected static $_tableDefsStr = array(
        self:: tableName => array(
            'fields' =>
                'id                     int auto_increment
                name                varchar(255)
                company             varchar(255)
                subject             varchar(255)
                phone               varchar(255)
                email               varchar(255)
                text                text

                add_date            datetime
                author_ip           varchar(15)
                '
        ,
            'indexes' => '
            primary id
            ',
            'engine' => 'InnoDB'
        )
    );






}


?>