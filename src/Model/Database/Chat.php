<?php

namespace Web\InterChat\Model\Database;
use JsonSerializable;

class Chat implements JsonSerializable{

    private int $id;
    private string $fromUser;
    private string $message;
    private string $targetUser;
    private string $timestamp;

    public function setId(string $id) {
        $this->id = $id;
    }

    public function setFromUser(string $fromUser) {
        $this->fromUser = $fromUser;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

    public function setTargetUser(string $targetUser) {
        $this->targetUser = $targetUser;
    }

    public function setTimestamp(string $timestamp) {
        $this->timestamp = $timestamp;
    }

    public function getId() {
        return $this->id;
    }
    
    public function getFromUser() {
        return $this->fromUser;
    }

    public function getMessage() {
        return $this->message;
    }

    public function getTargetUser() {
        return $this->targetUser;
    }

    public function getTimestamp() {
        return $this->timestamp;
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