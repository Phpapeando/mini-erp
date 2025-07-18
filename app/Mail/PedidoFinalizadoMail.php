<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoFinalizadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $carrinho;
    public array $endereco;
    public float $subtotal;
    public float $frete;
    public float $total;
    public string $numeroPedido;

    /**
     * Create a new message instance.
     */
    public function __construct(
        array $carrinho,
        array $endereco,
        float $subtotal,
        float $frete,
        string $numeroPedido
    ) {
        $this->carrinho = $carrinho;
        $this->endereco = $endereco;
        $this->subtotal = $subtotal;
        $this->frete = $frete;
        $this->total = $subtotal + $frete;
        $this->numeroPedido = $numeroPedido;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pedido Finalizado - #' . $this->numeroPedido,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido-finalizado',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
