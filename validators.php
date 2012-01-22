<?php

    function required($data) {
        if (empty($data))
            throw new Exception("Bu alan zorunludur.");
        return $data;
    }

    function email_validator($data) {
        if ($data && !eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $data))
            throw new Exception("Geçersiz email adresi.");
        return $data;
    }

?>