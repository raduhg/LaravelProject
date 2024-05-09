<style>
    * {
        box-sizing: border-box;
    }

    .grid-body {
        margin: 0;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        padding: 1rem;
    }

    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        padding: 0 4px;
    }

    .column, .column-splice {
        -ms-flex: 33%;
        flex: 33%;
        max-width: 33%;
        padding: 0 4px;
    }

    .column img, .column-splice img {
        margin-top: 8px;
        vertical-align: middle;
        width: 100%;
    }

    @media screen and (max-width: 800px) {
        .column {
            -ms-flex: 50%;
            flex: 50%;
            max-width: 50%;
        }

        .column-splice {
            display: flex;
            flex-direction: column;
            -ms-flex: 100%;
            flex: 100%;
            max-width: 100%;
            gap: 8px;
        }
    }

    @media screen and (max-width: 600px) {
        .column {
            -ms-flex: 100%;
            flex: 100%;
            max-width: 100%;
        }

        .column-splice {
            display: flex;
            flex-direction: column;
            -ms-flex: 100%;
            flex: 100%;
            max-width: 100%;
            gap: 0;
        }
    }
</style>
<div class="grid-body">
    <div class="row">
        <div class="column">
            <img src="/images/team_at_podragu.jpeg" >
            <img src="/images/team_on_moldoveanu.jpeg" >
        </div>

        <div class="column">
            <img src="/images/group_photo_bucura.jpeg" >
            <img src="/images/team_at_piatra_secuiului.jpeg" >
        </div>

        <div class="column-splice">
            <img src="/images/team_at_sambata1.jpeg" >
            <img src="/images/team_at_batrana.jpeg" >
        </div>
    </div>
    </div>
