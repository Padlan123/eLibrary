<?php

namespace App\Traits;

trait WithBookValidationUpdate
{
    protected function rulesUpdate()
    {
        return [
            'update_title' => 'required',
            'update_book_categories' => 'required',
            'update_author' => 'required',
            'update_publisher' => 'required',
            'update_publication_year' => 'required',
            'update_summary' => 'required',
            'update_subscription' => 'required',
            'update_cover_file_name' => 'nullable|file|mimes:png,jpeg,jpe',
            'update_pdf_file_name' => 'nullable|file|mimes:pdf'
        ];
    }

    protected function messagesUpdate()
    {
        return [
            'update_title.required' => 'tuliskan judul buku',
            'update_book_categories.required' => 'masukkan kategori',
            'update_book_categories.min' => 'masukkan kategori minimal 1',
            'update_author.required' => 'tuliskan penulis buku',
            'update_publisher.required' => 'tuliskan penerbit buku',
            'update_summary.required' => 'tuliskan sinopsis buku',
            'update_subscription.required' => 'tuliskan hak akses buku',
            'update_publication_year.required' => 'tuliskan tahun terbit buku',
            'update_cover_file_name.file' => 'masukkan file',
            'update_pdf_file_name.file' => 'masukkan file',
            'update_cover_file_name.mimes' => 'format file salah',
            'update_pdf_file_name.mimes' => 'format file salah',
        ];
    }

    protected function validateBookUpdate()
    {
        return $this->validate(
            $this->rulesUpdate(),
            $this->messagesUpdate()
        );
    }
}
