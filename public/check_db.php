<?php
try {
    if (\Illuminate\Support\Facades\Schema::hasTable('announcements')) {
        echo "Table 'announcements' EXISTS.";
    } else {
        echo "Table 'announcements' DOES NOT EXIST.";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
