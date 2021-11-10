<?php

function res($code, $status, $message, $data = []){
    return response()->json([
        'code' => $code,
        'status' => $status,
        'message' => $message,
        'data'  => $data]);
}