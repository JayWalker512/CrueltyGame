<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PasswordForm
 *
 * @author jaywalker
 */
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class PasswordForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('old_password', 'string')
            ->addField('new_password', ['type' => 'string'])
            ->addField('confirm_new_password', ['type' => 'string']);
    }

    protected function _buildValidator(Validator $validator)
    {
        return $validator->scalar('new_password')
                    ->lengthBetween('new_password', [8, 64])
                    ->notEmpty('new_password')
                    ->notEmpty('old_password')
                    ->notEmpty('confirm_new_password')
                    ->add('confirm_new_password', 'custom', [
                        'rule' => function ($value, $context) {
                            if ($value != $context['data']['new_password']) {
                                return false;
                            }
                            return true;
                        },
                        'message' => 'Passwords do not match'
                    ]);

            /*$validator->add('name', 'length', [
                'rule' => ['minLength', 10],
                'message' => 'A name is required'
            ])->add('email', 'format', [
                'rule' => 'email',
                'message' => 'A valid email address is required',
            ]);*/
    }

    protected function _execute(array $data)
    {
        // Send an email.
        return true;
    }
}