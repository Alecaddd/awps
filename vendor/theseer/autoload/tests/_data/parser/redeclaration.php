<?php
if (class_exists('My_Exception')) {
    abstract class demo extends My_Exception
    {
    }
} else {
    abstract class demo extends Exception
    {
    }
}