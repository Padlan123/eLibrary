<?php

namespace App\Traits;

trait WithTransactionValidation
{
    protected function rules()
    {
        return [
            'namaPengirim' => 'required',
            'nomorPengirim' => 'required',
            'buktiPembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'pilihanPaketId' => 'required|exists:pakets,id',
        ];
    }

    protected function messages()
    {
        return [
            'namaPengirim.required' => 'Nama pengirim perlu diisi',
            'nomorPengirim.required' => 'Nomor pengirim perlu diisi',
            'buktiPembayaran.required' => 'file perlu diunggah',
            'buktiPembayaran.image' => 'file harus berupa gambar',
            'buktiPembayaran.mimes' => 'file harus berformat jpeg, png, atau jpg',
            'buktiPembayaran.max' => 'Ukuran file maksimal 2MB',
            'pilihanPaketId.required' => 'Paket perlu dipilih',
            'pilihanPaketId.exists' => 'Paket yang dipilih tidak valid',
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
