<style>
    .logo-fade {
        width: 300px; /* Set your desired logo width */
        height: auto; /* Maintain aspect ratio */
        margin-top: 4rem;
        display: inline-block;
        position: relative;
        overflow: hidden;
        mask-image: radial-gradient(circle, white 50%,transparent 90%);
    }
</style>

<div class="flex flex-row">
    <img src="{{ asset('images/logo2.png') }}" alt="App Logo" class="logo-fade" />
</div>