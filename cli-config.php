<?php

// cli-config.php
require_once 'my_bootstrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);