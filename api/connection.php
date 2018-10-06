<?php
$conn = mysqli_connect("localhost", "libro", "LibroAPI", "libro") or die("Connection failed: " . mysqli_connect_error());
mysqli_set_charset($conn, "utf8");

function sqlmatches($sql)
{
    preg_match_all('/(\S[^:]+): (\d+)/', $sql->info, $matches);
    return $matches[2][0];
}