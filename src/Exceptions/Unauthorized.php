<?php

namespace TremulantTech\Veeqo\Exceptions;

/**
 * 401: bad credential, or the account revoked the app
 */
class Unauthorized extends VeeqoRequestException {}
