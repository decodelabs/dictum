<?php
/**
 * This is a stub file for IDE compatibility only.
 * It should not be included in your projects.
 */
namespace DecodeLabs;
use DecodeLabs\Veneer\Proxy;
use DecodeLabs\Veneer\ProxyTrait;
use DecodeLabs\Dictum\Context as Inst;
class Dictum implements Proxy { use ProxyTrait; 
const VENEER = 'Dictum';
const VENEER_TARGET = Inst::class;
const PLUGINS = Inst::PLUGINS;
public static $number;
public static $time;};
