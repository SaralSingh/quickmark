<?php

test('the application returns a successful response', function () {
    $this->withoutExceptionHandling();
    
    $response = $this->get('/');

    $response->assertStatus(200);
});
