<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Command;
use Illuminate\Validation\Factory as ValidatorFactory;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photolife:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the superadmin account';

    /**
     * @var ValidatorFactory
     */
    protected $validatorFactory;

    /**
     * CreateSuperAdmin constructor.
     * @param ValidatorFactory $validatorFactory
     */
    public function __construct(ValidatorFactory $validatorFactory)
    {
        parent::__construct();

        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Let's create a superadmin account!");
        $email = $this->setEmail();
        $password = $this->setPassword();
        $user = User::create([
            'name' => 'Admin',
            'email' => $email,
            'role' => 'superadmin',
        ]);
        $user->password = bcrypt($password);
        $user->save();
        $this->info('Your account has been created');
    }

    /**
     * Prompt user to enter email and validate it.
     *
     * @return string $email
     */
    private function setEmail()
    {
        $email = $this->ask('Enter an email');
        if ($this->validateEmail($email)) {
            return $email;
        } else {
            $this->error('Your email is not valid');
            $this->setEmail();
        }
    }

    /**
     * Prompt user to enter password, confirm and validate it.
     *
     * @return string $password
     */
    private function setPassword()
    {
        $password = $this->secret('Enter a password');
        if ($this->validatePassword($password)) {
            $confirmPassword = $this->secret('Confirm the password');
            if ($password === $confirmPassword) {
                return $password;
            } else {
                $this->error('Password does not match the confirm password');
                $this->setPassword();
            }
        } else {
            $this->error('Your password is not valid, at least 6 characters');
            $this->setPassword();
        }
    }

    /**
     * Determine if the email address given valid.
     *
     * @param  string $email
     * @return bool
     */
    private function validateEmail($email)
    {
        return $this->validatorFactory->make(['email' => $email], [
            'email' => 'required|email|max:255|unique:users',
        ])->passes();
    }

    /**
     * Determine if the password given valid.
     *
     * @param  string $password
     * @return bool
     */
    private function validatePassword($password)
    {
        return $this->validatorFactory->make(['password' => $password], [
            'password' => 'required|min:6',
        ])->passes();
    }
}
