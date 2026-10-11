<?php

namespace TremulantTech\Veeqo\Exceptions;

/** 401; on a stored access token, the Veeqo account revoked the app. */
class Unauthorized extends VeeqoRequestException {}
