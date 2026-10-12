                Schema::create('savings_transactions_new', function (Blueprint $table) {
                    $table->id();
                    $table->string('transaction_number')->unique();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->foreignId('sacco_id')->nullable()->constrained('sacos')->onDelete('restrict');
                    $table->decimal('amount', 15, 2);
                    $table->enum('transaction_type', ['deposit', 'withdrawal']);
                    $table->date('transaction_date');
                    $table->string('receipt_number')->nullable();
                    $table->text('notes')->nullable();
                    $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
                    $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
                    $table->decimal('balance_after', 15, 2);
                    $table->timestamps();
                    $table->softDeletes();

                    $table->index(['sacco_id', 'created_at']);
                });
