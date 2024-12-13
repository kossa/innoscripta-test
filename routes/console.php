<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:load-news')->daily();
