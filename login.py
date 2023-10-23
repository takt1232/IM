import tkinter as tk
import customtkinter as ctk
from tkinter import ttk
from typing import Optional, Tuple, Union
import tkinter.messagebox as messagebox
import pymysql

#sql connection
class SqlConnection():
    def __init__(self, host, username, password, database):
        self.host = host
        self.username = username
        self.password = password
        self.database = database
        self.connection = None
    
    def connect(self):
        self.connection = pymysql.connect(host=self.host, user=self.username, password=self.password, database=self.database)

    def execute(self, query, values):
        if values:
            self.connect()
            cursor = self.connection.cursor()
            cursor.execute(query, values)

        else:
            messagebox.showerror("Value Error!", "Code Error (line: 27)")

        return cursor.fetchall()
    
    def execute_fetch(self, query):
        self.connect()
        cursor = self.connection.cursor()
        cursor.execute(query)

        return cursor.fetchall()
    
    def execute_wnr(self, query, values):
        if values:
            self.connect()
            cursor = self.connection.cursor()
            cursor.execute(query, values)
            self.connection.commit()
            self.close_connection()
            messagebox.showinfo("Insert Success!", "Product Added Successfully")
        else:
            messagebox.showerror("Value Error!", "Code Error (line:38)")

    
    def close_connection(self):
        self.connection.close()

#appearance
ctk.set_appearance_mode("System")
ctk.set_default_color_theme("blue")

#main window
class LoginWindow(ctk.CTk):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.geometry("640x480")
        self.title("Login Page")
        self.resizable(False, False)
        self.configure(fg_color="#23272D")
        self.username = tk.StringVar(value="admin")
        self.password = tk.StringVar(value="admin")
        self.sql_connection = SqlConnection("sql6.freesqldatabase.com", "sql6631084", "NA3EVGKuc8", "sql6631084")

        #login form
        login_form = ctk.CTkFrame(self, width=320, height=300, corner_radius=10, fg_color="#33383F")
        login_form.place(relx=0.5,rely=0.5, anchor=tk.CENTER)

        #title
        login_title = ctk.CTkLabel(login_form, text="Login Page", font=("Century Gothic", 24, "bold"), text_color="#E3E3E3")
        login_title.place(x=100,y=20)

        #entries with labels
        username_label = ctk.CTkLabel(login_form, text="Username", text_color="#E3E3E3")
        username_label.place(x=90,y=60)
        username_entry = ctk.CTkEntry(login_form, textvariable=self.username)
        username_entry.place(x=90,y=90)

        password_label = ctk.CTkLabel(login_form, text="Password", text_color="#E3E3E3")
        password_label.place(x=90,y=130)
        password_entry = ctk.CTkEntry(login_form, textvariable=self.password, show="*")
        password_entry.place(x=90,y=160)

        #button
        login_btn = ctk.CTkButton(login_form, text="Login", command=self.login)
        login_btn.place(x=90,y=210)

    #login function
    def login(self):
        if self.username.get() and self.password.get():
            query = "SELECT * FROM users WHERE username = %s AND password = %s"
            values = (self.username.get(), self.password.get())
            db_user_info = self.sql_connection.execute(query, values)
            if db_user_info:
                self.destroy()
                homepage_window = HomepageWindow()
                homepage_window.mainloop()
            else:
                messagebox.showerror("Login Failed!", "Invalid Username or Password")
        else:
            messagebox.showerror("Login Failed!", "Username or Password is empty")


#homepage window
class HomepageWindow(ctk.CTk):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.geometry("640x480")
        self.title("Home Page")
        self.resizable(False, False)
        self.configure(fg_color="#23272D")
        self.user_position = 1
        self.sql_connection = SqlConnection("sql6.freesqldatabase.com", "sql6631084", "NA3EVGKuc8", "sql6631084")

        #title
        homepage_title = ctk.CTkLabel(self, height=50, text="Inventory System Management", font=("Century Gothic", 24, "bold"), text_color="#E3E3E3", bg_color="#33383F")
        homepage_title.pack(fill="x")

        #navbar
        homepage_navbar = ctk.CTkFrame(self, height=25, corner_radius=0)
        homepage_navbar.pack(fill="x")

        #product button
        homepage_navbar_btn1 = ctk.CTkButton(homepage_navbar, text="Product", corner_radius=0, border_color="#E3E3E3", border_width=2)
        homepage_navbar_btn1.grid(row=0,column=1,padx=8)

        #store button
        homepage_navbar_btn2 = ctk.CTkButton(homepage_navbar, text="Store", corner_radius=0, border_color="#E3E3E3", border_width=2)
        homepage_navbar_btn2.grid(row=0,column=2,padx=8)

        #supplier
        homepage_navbar_btn3 = ctk.CTkButton(homepage_navbar, text="Supplier", corner_radius=0, border_color="#E3E3E3", border_width=2)
        homepage_navbar_btn3.grid(row=0,column=3,padx=8)

        #settings
        homepage_navbar_btn4 = ctk.CTkButton(homepage_navbar, text="Settings", corner_radius=0, border_color="#E3E3E3", border_width=2)
        homepage_navbar_btn4.grid(row=0,column=4,padx=8)

        if self.user_position == 1:
            self.product()

    def product(self):
        # frame
        product_frame = ttk.Frame(self)
        product_frame.pack(fill="both", pady=10)

        # list frame
        product_list_frame = ttk.Labelframe(product_frame, text="Product List")
        product_list_frame.pack(side="left", fill="x", padx=10, pady=10)
        list_scroll = ttk.Scrollbar(product_list_frame)
        list_scroll.pack(side="right", fill="y")

        # list
        cols = ("Name", "Quantity", "Price", "Supplier")
        self.product_list = ttk.Treeview(product_list_frame, show="headings", yscrollcommand=list_scroll.set, columns=cols, height=15)

        for col in cols:
            self.product_list.heading(col, text=col)

        self.product_list.column("Name", width=100)
        self.product_list.column("Quantity", width=75)
        self.product_list.column("Price", width=50)
        self.product_list.column("Supplier", width=100)
        self.product_list.pack(side="left")
        list_scroll.config(command=self.product_list.yview)

        # field frame
        product_field_frame = ttk.Labelframe(product_frame, text="Product Information")
        product_field_frame.pack(side="left", fill="both", expand=True, padx=10, pady=10)

        # entries
        self.product_name_label = ctk.CTkLabel(product_field_frame, text="Name", font=("Century Gothic", 12))
        self.product_name_label.grid(row=0, padx=10, pady=10)
        self.product_name_entry = ctk.CTkEntry(product_field_frame)
        self.product_name_entry.grid(row=0, column=1, pady=10)

        self.product_quantity_label = ctk.CTkLabel(product_field_frame, text="Quantity", font=("Century Gothic", 12))
        self.product_quantity_label.grid(row=1, padx=10, pady=10)
        self.product_quantity_entry = ctk.CTkEntry(product_field_frame)
        self.product_quantity_entry.grid(row=1, column=1, pady=10)

        self.product_price_label = ctk.CTkLabel(product_field_frame, text="Price", font=("Century Gothic", 12))
        self.product_price_label.grid(row=2, padx=10, pady=10)
        self.product_price_entry = ctk.CTkEntry(product_field_frame)
        self.product_price_entry.grid(row=2, column=1, pady=10)

        self.product_supplier_label = ctk.CTkLabel(product_field_frame, text="Supplier", font=("Century Gothic", 12))
        self.product_supplier_label.grid(row=3, padx=10, pady=10)
        self.product_supplier_entry = ctk.CTkEntry(product_field_frame)
        self.product_supplier_entry.grid(row=3, column=1, pady=10)

        # buttons
        product_delete_btn = ctk.CTkButton(product_field_frame, width=60, text="Delete", corner_radius=0, bg_color="#F95F76")
        product_delete_btn.grid(row=4, pady=40)

        product_edit_btn = ctk.CTkButton(product_field_frame, width=60, text="Edit", corner_radius=0, bg_color="#F9E75F")
        product_edit_btn.grid(row=4, column=1, pady=40)

        product_add_btn = ctk.CTkButton(product_field_frame, width=60, text="Add", corner_radius=0, bg_color="#5FF997", command=self.add_product)
        product_add_btn.grid(row=4, column=2, pady=40)

        self.display_product_data(self.product_list)

    def add_product(self):
        if self.product_name_entry.get() and self.product_quantity_entry.get() and self.product_price_entry.get() and self.product_supplier_entry.get():
            try:
                quantity = int(self.product_quantity_entry.get())
                price = int(self.product_price_entry.get())

                # Insert the product into the database
                query = "INSERT INTO product (product_name, product_quantity, product_price, product_supplier) VALUES (%s, %s, %s, %s)"
                values = (self.product_name_entry.get(), quantity, price, self.product_supplier_entry.get())
                self.sql_connection.execute_wnr(query, values)

                self.display_product_data(self.product_list)

            except ValueError:
                messagebox.showerror("Invalid Input", "Quantity and price must be valid integer values.")
                return

        else:
            messagebox.showerror("Add Failed!", "One or more entry/entries is/are empty!")

    
    def display_product_data(self, product_list):
        product_list.delete(*product_list.get_children())

        query = "SELECT product_name, product_quantity, product_price, product_supplier FROM product"
        data = self.sql_connection.execute_fetch(query)
        for row in data:
            product_list.insert("", "end", values=row)
        
#run
login_window = LoginWindow()
login_window.mainloop()
