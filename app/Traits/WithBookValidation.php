<?php

namespace App\Traits;

trait WithBookValidation
{
    protected function rules()
    {
        return [
            'title' => 'required',
            'book_categories' => 'required',
            'author' => 'required',
            'publisher' => 'required',
            'publication_year' => 'required',
            'summary' => 'required',
            'subscription' => 'required',
            'cover_file_name' => 'required|file|mimes:png,jpeg,jpe',
            'pdf_file_name' => 'required|file|mimes:pdf'

        ];
    }

    protected function messages()
    {
        return [
            'title.required' => 'tuliskan judul buku',
            'book_categories.required' => 'masukkan kategori',
            'book_categories.min' => 'masukkan kategori minimal 1',
            'author.required' => 'tuliskan penulis buku',
            'publisher.required' => 'tuliskan penerbit buku',
            'summary.required' => 'tuliskan sinopsis buku',
            'subscription.required' => 'tuliskan hak akses buku',
            'publication_year.required' => 'tuliskan tahun terbit buku',
            'cover_file_name.required' => 'masukkan cover buku',
            'pdf_file_name.required' => 'masukkan file buku',
            'cover_file_name.file' => 'masukkan file',
            'pdf_file_name.file' => 'masukkan file',
            'cover_file_name.mimes' => 'format file salah',
            'pdf_file_name.mimes' => 'format file salah',
        ];
    }

    protected function validateBook()
    {
        return $this->validate(
            $this->rules(),
            $this->messages()
        );
    }
}
