<?php
/**
 * Created by PhpStorm.
 * User: Specialist001
 * Date: 21/10/21
 * Time: 12:10
 */

namespace Specialist\Feedback\Classes;


interface Method
{
    /**
     * Used to register new form fields to Channel.
     * Modify and prepare Channel model.
     *
     * @return void
     */
    public function boot();

    /**
     * @param array $methodData
     * @param array $data
     * @return mixed
     */
    public function send($methodData, $data);

}