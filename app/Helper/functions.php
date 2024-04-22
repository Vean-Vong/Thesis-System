<?php

if(!function_exists('filterMonth')) {
    function filterMonth($month)
    {
        if($month == 1) {
            return 'មករា';
        } else if($month == 2) {
            return 'កុម្ភៈ';
        } else if($month == 3) {
            return 'មីនា';
        } else if($month == 4) {
            return 'មេសា';
        } else if($month == 5) {
            return 'ឧសភា';
        } else if($month == 6) {
            return 'មិថុនា';
        } else if($month == 7) {
            return 'កក្កដា';
        } else if($month == 8) {
            return 'សីហា';
        } else if($month == 9) {
            return 'កញ្ញា';
        } else if($month == 10) {
            return 'តុលា';
        } else if($month == 11) {
            return 'វិច្ឆិកា';
        } else if($month == 12) {
            return 'ធ្នូ';
        }
    }
}

if(!function_exists('calcGrade')) {
    function calcGrade($avg)
    {
        if($avg >= 9.5) {
            return "ល្អណាស់";
        } elseif($avg >= 8) {
            return "ល្អ";
        } else if($avg >= 6.5) {
            return "ល្អបង្គួរ";
        } else if($avg >= 5) {
            return "មធ្យម";
        } else if($avg < 5) {
            return "ខ្សោយ";
        } else {
            return "មិនចាត់ថ្នាក់";
        }
    }
}
