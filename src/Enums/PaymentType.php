<?php

namespace TremulantTech\Veeqo\Enums;

enum PaymentType: string
{
    case None = 'none';
    case Cash = 'cash';
    case CreditCard = 'credit_card';
    case BankTransfer = 'bank_transfer';
    case Paypal = 'paypal';
    case PaypalExpress = 'paypal_express';
    case Sagepay = 'sagepay';
    case Checkmo = 'checkmo';
    case OnlineFinance = 'online_finance';
}
