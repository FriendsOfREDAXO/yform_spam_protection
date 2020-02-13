<?php

if ($subpage = rex_be_controller::getCurrentPagePart(2)) {
    include rex_be_controller::getCurrentPageObject()->getSubPath();
}
