<?php

namespace App\Controllers;

/**
 * Contact — public contact page + best-effort form handler.
 */
class Contact extends BaseController
{
    public function index()
    {
        return view('pages/contact', ['activeNav' => 'contact']);
    }

    public function submit()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[120]',
            'email'   => 'required|valid_email|max_length[180]',
            'message' => 'required|min_length[5]',
        ];

        if (! $this->validate($rules)) {
            // Re-show the form with errors and the previously entered values.
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $name    = trim((string) $this->request->getPost('name'));
        $email   = trim((string) $this->request->getPost('email'));
        $message = trim((string) $this->request->getPost('message'));

        // Best-effort email — never fail the request if SMTP is unavailable.
        try {
            $mailer = \Config\Services::email();
            $mailer->setFrom('contact@teacherpedia.co.uk', 'Teacherpedia Contact');
            $mailer->setReplyTo($email, $name);
            $mailer->setTo('contact@teacherpedia.co.uk');
            $mailer->setSubject('Contact form: ' . $name);
            $mailer->setMessage(
                'From: ' . esc($name) . ' <' . esc($email) . ">\n\n" . esc($message)
            );
            $mailer->send();
        } catch (\Throwable $e) {
            log_message('error', 'Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->to(site_url('contact'))
            ->with('success', "Thanks for getting in touch — we read every message and aim to reply within a couple of days.");
    }
}
