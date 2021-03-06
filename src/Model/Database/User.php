<?php

namespace Web\InterChat\Model\Database;
use JsonSerializable;

class User implements JsonSerializable{
    
    private string $username;
    private string $name;
    private string $password;

    public function setUsername(string $username) {
        $this->username = $username;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getName(): string {
        return $this->name;
    }
    
    public function getPassword(): string {
        return $this->password;
    }
	/**
	 * Specify data which should be serialized to JSON
	 * Serializes the object to a value that can be serialized natively by json_encode().
	 *
	 * @return mixed Returns data which can be serialized by json_encode(), which is a value of any type other than a resource .
	 */
	function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
	}
}