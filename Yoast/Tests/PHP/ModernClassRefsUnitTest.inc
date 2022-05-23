<?php

echo __CLASS__; // self::class

echo get_class(); // self::class

echo \get_class(); // self::class

echo self::class; // Correct.

echo $this->get_class(); // Correct.
  
echo self::get_class(); // Correct.

echo Class_Name::get_class(); // Correct.
  
echo $object?->get_class(); // Correct.
