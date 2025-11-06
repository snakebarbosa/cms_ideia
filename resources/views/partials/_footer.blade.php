

<footer>
    <div class="footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Artigos </h3>
                    <ul>

                        <li>  <a href="{{ URL::to('/') }}/Administrator/Artigo"> Inserir Artigos</a> </li>
                         <li>  <a href="{{ URL::to('/') }}/Administrator/Artigo"> Artigos</a> </li>
                          <li>  <a href="{{ URL::to('/') }}/Administrator/Categoria/create"> Inserir Categoria</a> </li>
                        <li> <a href="{{ URL::to('/') }}/Administrator/Categoria"> Categoria</a> </li>

                    </ul>
                </div>
                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Documentos </h3>
                    <ul>
                         <li>  <a href="{{ URL::to('/') }}/Administrator/Documento/create"> Inserir Documentos</a> </li>
                         <li>  <a href="{{ URL::to('/') }}/Administrator/Documento"> Documentos</a> </li>
                         <li>  <a href="{{ URL::to('/') }}/Administrator/Documentacao/createDoc"> Inserir Categoria</a> </li>
                        <li> <a href="{{ URL::to('/') }}/Administrator/Documentacao/categoria"> Pasta</a> </li>

                    </ul>
                </div>
                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Imagem </h3>
                    <ul>

                              <li>  <a href="{{ URL::to('/') }}/Administrator/Imagem"> Galeria</a> </li>
                        <li> <a href="{{ URL::to('/') }}/Administrator/Midia/categoria"> Pastas</a> </li>

                    </ul>
                </div>
                <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
                    <h3> Tag</h3>
                    <ul>
                          <li>  <a href="{{ URL::to('/') }}/Administrator/Tag/create"> Inserir Tag</a> </li>
                           <li>  <a href="{{ URL::to('/') }}/Administrator/Tag"> Tag</a> </li>
                    </ul>
                </div>
                <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12 ">
                    <h3> Redes Sociais </h3>

                    <ul class="social">
                        <li> <a href="https://www.facebook.com/agenciadeaviacaocivil/"> <i class=" fa fa-facebook">   </i> </a> </li>
                        <li> <a href="#"> <i class="fa fa-twitter">   </i> </a> </li>
                        <li> <a href="#"> <i class="fa fa-google-plus">   </i> </a> </li>
                        <li> <a href="#"> <i class="fa fa-youtube">   </i> </a> </li>
                    </ul>
                </div>
            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!--/.footer-->

    <div class="footer-bottom bg_azul">
        <div class="container">
            <p class="pull-left"> {{ $boinfo['footer'] }} </p>
            <div class="pull-right">

            </div>
        </div>
    </div>
    <!--/.footer-bottom-->
</footer>