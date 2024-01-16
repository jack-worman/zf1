<?php

#[AllowDynamicProperties]
class Zend_Acl_UseCase1_UserIsBlogPostOwnerAssertion implements Zend_Acl_Assert_Interface
{
    public $lastAssertRole;
    public $lastAssertResource;
    public $lastAssertPrivilege;
    public $assertReturnValue = true;

    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $user = null, Zend_Acl_Resource_Interface $blogPost = null, $privilege = null)
    {
        $this->lastAssertRole = $user;
        $this->lastAssertResource = $blogPost;
        $this->lastAssertPrivilege = $privilege;

        return $this->assertReturnValue;
    }
}
