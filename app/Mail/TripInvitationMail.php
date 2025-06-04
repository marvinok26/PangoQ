<?php

namespace App\Mail;

use App\Models\TripInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TripInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(TripInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this->subject('You\'re invited to join a trip to ' . $this->invitation->trip->destination)
                    ->view('emails.trip-invitation')
                    ->with([
                        'invitation' => $this->invitation,
                        'trip' => $this->invitation->trip,
                        'inviter' => $this->invitation->invitedBy,
                        'acceptUrl' => $this->invitation->acceptance_url,
                        'declineUrl' => $this->invitation->decline_url,
                    ]);
    }
}

// ==================== TRIP INVITATION MIGRATION ====================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripInvitationsTable extends Migration
{
    public function up()
    {
        Schema::create('trip_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('invited_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('email');
            $table->string('name')->nullable();
            $table->text('message')->nullable();
            $table->enum('role', ['member', 'organizer', 'viewer'])->default('member');
            $table->enum('status', ['pending', 'accepted', 'declined', 'expired'])->default('pending');
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamps();

            $table->index(['email', 'trip_id']);
            $table->index(['token']);
            $table->index(['status']);
            $table->index(['expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_invitations');
    }
}