@extends('backend.template.layouts.master')

@section('title') Contact User List @endsection

@section('content')

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">User List</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Contacts</a></li>
                                            <li class="breadcrumb-item active">User List</li>
                                        </ol>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap table-hover">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col" style="width: 70px;">#</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Tags</th>
                                                        <th scope="col">Projects</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="avatar-xs">
                                                                <span class="avatar-title rounded-circle">
                                                                    D
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">David McHenry</a></h5>
                                                            <p class="text-muted mb-0">UI/UX Designer</p>
                                                        </td>
                                                        <td>david@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Photoshop</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">illustrator</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            125
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <img class="rounded-circle avatar-xs" src="backend/images/users/avatar-2.jpg" alt="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">Frank Kirk</a></h5>
                                                            <p class="text-muted mb-0">Frontend Developer</p>
                                                        </td>
                                                        <td>frank@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Html</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Css</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">2 + more</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            132
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <img class="rounded-circle avatar-xs" src="backend/images/users/avatar-3.jpg" alt="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">Rafael Morales</a></h5>
                                                            <p class="text-muted mb-0">Backend Developer</p>
                                                        </td>
                                                        <td>Rafael@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Php</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Java</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Python</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            112
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="avatar-xs">
                                                                <span class="avatar-title rounded-circle">
                                                                    M
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">Mark Ellison</a></h5>
                                                            <p class="text-muted mb-0">Full Stack Developer</p>
                                                        </td>
                                                        <td>mark@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Ruby</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Php</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">2 + more</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            121
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <img class="rounded-circle avatar-xs" src="backend/images/users/avatar-4.jpg" alt="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">Minnie Walter</a></h5>
                                                            <p class="text-muted mb-0">Frontend Developer</p>
                                                        </td>
                                                        <td>minnie@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Html</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Css</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">2 + more</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            145
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <img class="rounded-circle avatar-xs" src="backend/images/users/avatar-5.jpg" alt="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">Shirley Smith</a></h5>
                                                            <p class="text-muted mb-0">UI/UX Designer</p>
                                                        </td>
                                                        <td>shirley@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Photoshop</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">illustrator</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            136
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="avatar-xs">
                                                                <span class="avatar-title rounded-circle">
                                                                    J
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">John Santiago</a></h5>
                                                            <p class="text-muted mb-0">Full Stack Developer</p>
                                                        </td>
                                                        <td>john@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Ruby</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Php</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">2 + more</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            125
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div>
                                                                <img class="rounded-circle avatar-xs" src="backend/images/users/avatar-5.jpg" alt="">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <h5 class="font-size-14 mb-1"><a href="#" class="text-dark">Colin Melton</a></h5>
                                                            <p class="text-muted mb-0">Backend Developer</p>
                                                        </td>
                                                        <td>colin@skote.com</td>
                                                        <td>
                                                            <div>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Php</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Java</a>
                                                                <a href="#" class="badge badge-soft-primary font-size-11 m-1">Python</a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            136
                                                        </td>
                                                        <td>
                                                            <ul class="list-inline font-size-20 contact-links mb-0">
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                                                </li>
                                                                <li class="list-inline-item px-2">
                                                                    <a href="" data-toggle="tooltip" data-placement="top" title="Profile"><i class="bx bx-user-circle"></i></a>
                                                                </li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <ul class="pagination pagination-rounded justify-content-center mt-4">
                                                    <li class="page-item disabled">
                                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">1</a>
                                                    </li>
                                                    <li class="page-item active">
                                                        <a href="#" class="page-link">2</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">3</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">4</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link">5</a>
                                                    </li>
                                                    <li class="page-item">
                                                        <a href="#" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
@endsection
