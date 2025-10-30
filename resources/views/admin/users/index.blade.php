@extends('layouts.main')

@section('content')
 <div class="space-y-5 py-5 px-10">
    <div class="space-y-7 flex flex-col">
        <h1 class="text-gray-100 font-[600]">User Management</h1>
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h6 class="font-[600]">Parents Lists</h6>
                <p class="text-gray-910">1,234  Parents</p>
            </div>
            <button type="button"
                class="bg-gray-50 hover:bg-gray-200 rounded-full text-sm px-5 py-3 cursor-pointer w-full lg:w-[200px]">
                <span class="text-black text-[16px] font-semibold">Add New Parent</span>
            </button>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="space-y-10 divide-y divide-gray-200">
        <!-- TABLE SECTION -->
        <div class="space-y-5">
            <!-- TABLE SEARCH -->
            <div class="flex items-center w-full lg:w-[350px] bg-white border border-gray-280 rounded-full px-2 py-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-black text-white mr-3">
                    <img src="/admin/assets/icons/search.svg" alt="Icon">
                </div>
                <input type="text" class="flex-1 bg-transparent text-gray-700 placeholder:text-[#A6A1A1] focus:outline-none" placeholder="Search" />
            </div>

            <!-- START : TABLE -->
            <div class="overflow-x-auto border border-[#424242] rounded-[12px] mb-10">
                <table
                    class="min-w-full border border-[#424242] rounded-[12px] overflow-hidden text-sm text-left">
                    <thead class="bg-gray-800 text-gray-200">
                        <tr>
                            <th class="p-4 border border-[#424242]">No</th>
                            <th class="p-4 border border-[#424242]">Name</th>
                            <th class="p-4 border border-[#424242]">Email</th>
                            <th class="p-4 border border-[#424242]">Total Children</th>
                            <th class="p-4 border border-[#424242]">Total Subjects Enrolled</th>
                            <th class="p-4 border border-[#424242]">Status Account</th>
                            <th class="p-4 border border-[#424242]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-900 divide-y divide-gray-800 text-gray-50">
                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                            <td class="p-4 border border-gray-700">1</td>
                            <td class="p-4 border border-gray-700">
                                Muhammad Amir Asyraf Bin Long Name Muhammad Ahmad Syari Name Long...
                            </td>
                            <td class="p-4 border border-gray-700">
                                muhammadamiiiilongemmirlon@gmail.com
                            </td>
                            <td class="p-4 border border-gray-700">2</td>
                            <td class="p-4 border border-gray-700">5</td>
                            <td class="p-4 border border-gray-700 text-green-100">Active</td>
                            <td class="p-4 border border-gray-700">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="" class="flex flex-col items-center">
                                        <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                            <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5 text-black" />
                                        </div>
                                        <span class="text-[#5F5F5F] text-[10px]">View</span>
                                    </a>
                                    <a href="user-management-parent-edit-1.2.html" class="flex flex-col items-center">
                                        <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                            <img src="/admin/assets/icons/pencil.svg" alt="Icon" class="size-5 text-black" />
                                        </div>
                                        <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                            <td class="p-4 border border-gray-700">2</td>
                            <td class="p-4 border border-gray-700">
                                Muhammad Amir Asyraf Bin Long Name Muhammad Ahmad Syari Name Long...
                            </td>
                            <td class="p-4 border border-gray-700">
                                muhammadamiiiilongemmirlon@gmail.com
                            </td>
                            <td class="p-4 border border-gray-700">2</td>
                            <td class="p-4 border border-gray-700">5</td>
                            <td class="p-4 border border-gray-700 text-[#FF2147]">Suspend</td>
                            <td class="p-4 border border-gray-700">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="user-management-parent-view-1.2.html" class="flex flex-col items-center">
                                        <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                            <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5 text-black" />
                                        </div>
                                        <span class="text-[#5F5F5F] text-[10px]">View</span>
                                    </a>
                                    <a href="user-management-parent-edit-1.2.html" class="flex flex-col items-center">
                                        <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[35px] h-[35px] rounded-full p-2 flex items-center justify-center">
                                            <img src="/admin/assets/icons/pencil.svg" alt="Icon" class="size-5 text-black" />
                                        </div>
                                        <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="odd:bg-[#141414] even:bg-[#171717]">
                            <td class="p-4 border border-gray-700">3</td>
                            <td class="p-4 border border-gray-700">
                                Muhammad Amir Asyraf Bin Long Name Muhammad Ahmad Syari Name Long...
                            </td>
                            <td class="p-4 border border-gray-700">
                                muhammadamiiiilongemmirlon@gmail.com
                            </td>
                            <td class="p-4 border border-gray-700">2</td>
                            <td class="p-4 border border-gray-700">5</td>
                            <td class="p-4 border border-gray-700 text-[#E10BE1]">Restore</td>
                            <td class="p-4 border border-gray-700">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="user-management-parent-edit-1.2.html" class="flex flex-col items-center">
                                        <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[31px] h-[31px] rounded-full p-2 flex items-center justify-center">
                                            <img src="/admin/assets/icons/eye.svg" alt="Icon" class="size-5 text-black" />
                                        </div>
                                        <span class="text-[#5F5F5F] text-[10px]">View</span>
                                    </a>
                                    <a href="user-management-parent-edit-1.2.html" class="flex flex-col items-center">
                                        <div class="block bg-gray-50 hover:bg-gray-200 cursor-pointer w-[31px] h-[31px] rounded-full p-2 flex items-center justify-center">
                                            <img src="/admin/assets/icons/pencil.svg" alt="Icon" class="size-5 text-black" />
                                        </div>
                                        <span class="text-[#5F5F5F] text-[10px]">Edit</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- END : TABLE -->
        </div>
    </div>
</div>
@endsection