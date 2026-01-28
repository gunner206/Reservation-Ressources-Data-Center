@extends('layout')

@section('content')
<div style="padding: 40px; color: white; max-width: 600px; margin: 0 auto;">
    
    <h1 style="margin-bottom: 30px;">✏️ Modifier l'utilisateur</h1>

    <div style="background: #0f172a; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') <div style="margin-bottom: 20px;">
                <label style="display:block; color: #94a3b8; margin-bottom: 5px;">Nom de l'utilisateur</label>
                <input type="text" value="{{ $user->name }}" disabled 
                       style="width: 100%; padding: 10px; background: #1e293b; border: 1px solid #334155; color: white; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; color: #94a3b8; margin-bottom: 5px;">Email</label>
                <input type="text" value="{{ $user->email }}" disabled 
                       style="width: 100%; padding: 10px; background: #1e293b; border: 1px solid #334155; color: white; border-radius: 5px;">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display:block; color: #ed8936; margin-bottom: 5px; font-weight: bold;">Rôle / Permission</label>
                <select name="role" style="width: 100%; padding: 12px; background: #2d3748; border: 1px solid #4a5568; color: white; border-radius: 5px; cursor: pointer;">
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>🔵 Utilisateur (User)</option>
                    <option value="technicien" {{ $user->role == 'technicien' ? 'selected' : '' }}>🟡 Technicien</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>🟠 Administrateur</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" style="padding: 10px 20px; background: #48bb78; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
                    💾 Enregistrer
                </button>
                
                <a href="{{ route('users.index') }}" style="padding: 10px 20px; background: #4a5568; color: white; text-decoration: none; border-radius: 5px;">
                    Annuler
                </a>
            </div>
        </form>

    </div>
</div>
@endsection