<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; 
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Qualquer propriedade PÚBLICA será enviada para o Frontend (JavaScript).
     * Se for protected ou private, ela não vai.
     */
    public $message;

    /**
     * Crie uma nova instância do evento.
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Define em qual canal o evento será transmitido.
     */
    public function broadcastOn(): array
    {
        // 3. IMPORTANTE: Define o nome do canal
        // Use 'Channel' para público ou 'PrivateChannel' para privado.
        return [
            new Channel('chat-global'),
        ];
    }
    
    /**
     * (Opcional) Define um apelido para o evento.
     * Sem isso, o JS terá que ouvir por '.App\\Events\\MessageSent'
     * Com isso, o JS ouve apenas por '.message.sent' ou 'message.sent'
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}