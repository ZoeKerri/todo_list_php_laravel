import 'package:flutter/material.dart';
import 'package:to_do_list_app/models/auth_response.dart';
import 'package:to_do_list_app/utils/theme_config.dart';

class SearchUsersDialog extends StatefulWidget {
  final Function(User) onUserSelected;
  final Future<List<User>> Function(String) onSearch;
  final AppColors colors;
  final List<int> excludeUserIds; // Users to exclude from results

  const SearchUsersDialog({
    super.key,
    required this.onUserSelected,
    required this.onSearch,
    required this.colors,
    this.excludeUserIds = const [],
  });

  @override
  State<SearchUsersDialog> createState() => _SearchUsersDialogState();
}

class _SearchUsersDialogState extends State<SearchUsersDialog> {
  final TextEditingController _searchController = TextEditingController();
  List<User> _searchResults = [];
  bool _isSearching = false;
  String? _errorMessage;

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  void _performSearch(String query) async {
    if (query.trim().isEmpty) {
      setState(() {
        _searchResults = [];
        _isSearching = false;
        _errorMessage = null;
      });
      return;
    }

    setState(() {
      _isSearching = true;
      _errorMessage = null;
    });

    try {
      // Call the search function passed from parent
      final users = await widget.onSearch(query);
      setState(() {
        // Filter out excluded users
        _searchResults = users.where((user) => !widget.excludeUserIds.contains(user.id)).toList();
      });
    } catch (e) {
      setState(() {
        _errorMessage = 'Error searching: ${e.toString()}';
        _searchResults = [];
      });
    } finally {
      setState(() {
        _isSearching = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return AlertDialog(
      backgroundColor: widget.colors.itemBgColor,
      title: Text(
        'Add Member',
        style: TextStyle(color: widget.colors.textColor),
      ),
      content: SizedBox(
        width: double.maxFinite,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextField(
              controller: _searchController,
              style: TextStyle(color: widget.colors.textColor),
              decoration: InputDecoration(
                hintText: 'Enter email prefix',
                hintStyle: TextStyle(color: widget.colors.textColor.withOpacity(0.6)),
                enabledBorder: UnderlineInputBorder(
                  borderSide: BorderSide(color: widget.colors.textColor),
                ),
                focusedBorder: UnderlineInputBorder(
                  borderSide: BorderSide(color: widget.colors.textColor),
                ),
                suffixIcon: _isSearching
                    ? Padding(
                        padding: const EdgeInsets.all(12.0),
                        child: SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor: AlwaysStoppedAnimation<Color>(widget.colors.textColor),
                          ),
                        ),
                      )
                    : Icon(
                        Icons.search,
                        color: widget.colors.textColor,
                      ),
              ),
              onChanged: (value) {
                // Debounce search
                Future.delayed(Duration(milliseconds: 800), () {
                  if (_searchController.text == value) {
                    _performSearch(value);
                  }
                });
              },
            ),
            SizedBox(height: 16),
            if (_errorMessage != null)
              Padding(
                padding: const EdgeInsets.only(bottom: 8.0),
                child: Text(
                  _errorMessage!,
                  style: TextStyle(color: Colors.red, fontSize: 12),
                ),
              ),
            if (_searchResults.isEmpty && !_isSearching && _searchController.text.isNotEmpty)
              Padding(
                padding: const EdgeInsets.all(8.0),
                child: Text(
                  'No users found',
                  style: TextStyle(color: widget.colors.textColor.withOpacity(0.6)),
                ),
              ),
            if (_searchResults.isNotEmpty)
              Flexible(
                child: ListView.builder(
                  shrinkWrap: true,
                  itemCount: _searchResults.length,
                  itemBuilder: (context, index) {
                    final user = _searchResults[index];
                    return ListTile(
                      leading: CircleAvatar(
                        backgroundColor: widget.colors.primaryColor,
                        child: Text(
                          user.name.isNotEmpty ? user.name[0].toUpperCase() : user.email[0].toUpperCase(),
                          style: TextStyle(color: widget.colors.textColor),
                        ),
                      ),
                      title: Text(
                        user.name,
                        style: TextStyle(color: widget.colors.textColor),
                      ),
                      subtitle: Text(
                        user.email,
                        style: TextStyle(color: widget.colors.textColor.withOpacity(0.7)),
                      ),
                      onTap: () {
                        widget.onUserSelected(user);
                        Navigator.of(context).pop();
                      },
                    );
                  },
                ),
              ),
          ],
        ),
      ),
      actions: [
        TextButton(
          onPressed: () => Navigator.of(context).pop(),
          child: Text(
            'Cancel',
            style: TextStyle(color: widget.colors.textColor),
          ),
        ),
      ],
    );
  }
}

