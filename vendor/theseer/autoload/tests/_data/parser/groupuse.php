<?php
namespace some\name\space;

use foo\bar\InterfaceA as InterfaceX;

use my\other\{
    name\ClassA,
    name\InterfaceB as InterfaceC,
    some\InterfaceD
};

class ClassD extends ClassA implements InterfaceX, InterfaceC, \my\other\some\InterfaceD {

}
