<?php
// ئەم پەیجە تەنیا ئیشی لۆگئاوت کردنی یوزەرە
// Initialize the session
session_start(); // سەرەتا سێشنەکە دەست پێ ئەکەین تا زانیاری یوزەرەکە بدۆزینەوە
 
// Unset all of the session variables
$_SESSION = array(); // لێرەدا هەموو ئەو زانیاریانەی لە ئەڕڕەی سێشنی ئەو یوزەرەیا دروست مان کردبوو بەتاڵیان ئەکەینەوە کە واباشترە، ئەویش بە یەکسانکردنی سێشنەکە بە ئەڕڕەیەکی بەتاڵ
 
// Destroy the session.
session_destroy(); //  بەم کۆدە سێشنەکە لەناو ئەبرێ و یوزەرەکە لۆگئاوت ئەبێ
 
// Redirect to home page
header("location: index.php"); // دوای لۆگئاوت بوون با یوزەرەکە بچێتەوە بۆ پەیجی هۆم
exit;
?>