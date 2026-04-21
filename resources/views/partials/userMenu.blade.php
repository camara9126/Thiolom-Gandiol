<?php
    
    use App\Models\article;

    // Alert sotck
    $alerte = Article::produitsEnAlerte()->count();
?>

<div class="user-menu">
    @if($alerte)
        <div class="alert alert-danger">
            ⛔ Vous avez <b><?= $alerte ?></b> produit(s) en rupture de stock. Merci de mettre a jour !
        </div>
    @endif
    <div class="dropdown">
        <button class="d-flex align-items-center" type="button" data-bs-toggle="dropdown">
            <div class="user-avatar">
                <span>AD</span>
            </div>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2 text-primary"></i> Mon profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                            @csrf    
                    <a class="dropdown-item" href="{{route('logout')}}"onclick="event.preventDefault(); this.closest('form').submit();"><i class="fas fa-sign-out-alt me-2 text-danger"></i> Déconnexion</a>
                </form>
            </li>
        </ul>
    </div>  
    
</div>