<?php

namespace App\Traits;

trait WithTransactionValidation
{
    protected function rules()
    {
        return [
            'name' => 'required',
            'number' => 'required',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'packageSelectedId' => 'required|exists:packages,id',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Nama bank/e-wallet pengirim perlu diisi',
            'number.required' => 'Nomor bank/e-wallet pengirim perlu diisi',
            'payment_proof.required' => 'file perlu diunggah',
            'payment_proof.image' => 'file harus berupa gambar',
            'payment_proof.mimes' => 'file harus berformat jpeg, png, atau jpg',
            'payment_proof.max' => 'Ukuran file maksimal 2MB',
            'packageSelectedId.required' => 'Paket perlu dipilih',
            'packageSelectedId.exists' => 'Paket yang dipilih tidak valid',
        ];
    }

    protected function validateTransaction()
    {
        return $this->validate(
            $this->rules(),
            $this->messages()
        );
    }
}
